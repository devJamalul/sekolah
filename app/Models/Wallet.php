<?php

namespace App\Models;

use App\Models\ExpenseDetail;
use App\Models\Scopes\WalletScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "wallets";

    protected $casts = [
        'danabos' => 'boolean'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new WalletScope);
    }

    public function expense_detail(): HasMany
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function scopeDanaBos(Builder $query)
    {
        $query->where('danabos', true);
    }

    public function getBalanceAttribute()
    {
        return $this->attributes['init_value'] + $this->attributes['last_balance'];
    }

    public function wallet_logs(): HasMany
    {
        return $this->hasMany(WalletLog::class, 'wallet_id');
    }

    public function sempoa_wallet(): HasOne
    {
        return $this->hasOne(SempoaWallet::class);
    }
}
