<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StudentTuitionPaymentHistory;

class ExpenseReportDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = Expense::with('expense_details', 'expense_details.wallet')
        ->whereBetween('expenses.created_at', [
            session('expense_report_start')->startOfDay()->format('Y-m-d H:i:s'),
            session('expense_report_end')->endOfDay()->format('Y-m-d H:i:s'),
        ]);
        return DataTables::of($data)
            ->addColumn('total', fn (Expense $row) => 'Rp. ' .  number_format($row->expense_details()->sum(DB::raw('price * quantity')), 0, ', ', '.'))
            ->toJson();
    }
}
