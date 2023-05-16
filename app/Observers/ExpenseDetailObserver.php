<?php

namespace App\Observers;

use App\Models\ExpenseDetail;
use Illuminate\Support\Facades\DB;

class ExpenseDetailObserver
{
    /**
     * Handle the ExpenseDetail "created" event.
     */
    public function created(ExpenseDetail $expenseDetail): void
    {
        $wholePrice = ExpenseDetail::where('expense_id', $expenseDetail->expense_id)->sum(DB::raw('price * quantity'));

        $expenseDetail->expense()->update([
            'price' => $wholePrice
        ]);
    }

    /**
     * Handle the ExpenseDetail "updated" event.
     */
    public function updated(ExpenseDetail $expenseDetail): void
    {
        self::created($expenseDetail);
    }

    /**
     * Handle the ExpenseDetail "deleted" event.
     */
    public function deleted(ExpenseDetail $expenseDetail): void
    {
        self::created($expenseDetail);
    }

    /**
     * Handle the ExpenseDetail "restored" event.
     */
    public function restored(ExpenseDetail $expenseDetail): void
    {
        self::created($expenseDetail);
    }

    /**
     * Handle the ExpenseDetail "force deleted" event.
     */
    public function forceDeleted(ExpenseDetail $expenseDetail): void
    {
        self::created($expenseDetail);
    }
}
