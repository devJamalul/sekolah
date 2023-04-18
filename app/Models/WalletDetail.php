<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'table_wallet_logs';

    const CASHFLOW_TYPE_IN = 'in';

    const CASHFLOW_TYPE_OUT = 'out';
}
