<?php

namespace App\Models;

use App\Models\Scopes\SempoaConfigurationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SempoaConfiguration extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_OPEN = 'open';
    const STATUS_LOCKED = 'locked';

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new SempoaConfigurationScope);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
