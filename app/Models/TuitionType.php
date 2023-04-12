<?php

namespace App\Models;

use App\Models\School;
use App\Models\Scopes\TuitionTypeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TuitionType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'recurring' => 'boolean'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TuitionTypeScope);
    }

    public function student_tuitions(): HasMany
    {
        return $this->hasMany(StudentTuition::class);
    }

    public function tuitions(): HasMany
    {
        return $this->hasMany(Tuition::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
