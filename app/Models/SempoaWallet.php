<?php

namespace App\Models;

use App\Models\Scopes\SempoaConfigurationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SempoaWallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new SempoaConfigurationScope);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
