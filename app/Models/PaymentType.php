<?php

namespace App\Models;

use App\Models\School;
use App\Models\Scopes\PaymentTypeScope;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new PaymentTypeScope);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student_tuitions(): HasMany
    {
        return $this->hasMany(StudentTuition::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
