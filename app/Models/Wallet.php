<?php

namespace App\Models;

use App\Models\ExpenseDetail;
use App\Models\Scopes\WalletScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new WalletScope);
    }

    public function expense_detail(): HasMany
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function getBalanceAttribute()
    {   
        return $this->attributes['init_value'] + $this->attributes['last_balance'];
    }
}
