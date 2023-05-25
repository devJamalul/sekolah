<?php

namespace App\Models;

use App\Models\Wallet;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseDetail extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = [];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
    
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
