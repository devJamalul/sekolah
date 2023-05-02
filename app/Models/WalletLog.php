<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'wallet_logs';

    const CASHFLOW_TYPE_IN = 'in';

    const CASHFLOW_TYPE_OUT = 'out';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}
