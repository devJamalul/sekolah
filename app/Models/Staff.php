<?php

namespace App\Models;

use App\Models\User;
use App\Models\School;
use App\Models\ClassroomStaff;
use App\Models\Scopes\StaffScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    const GENDER_MALE = "Laki-laki";
    const GENDER_FEMALE = "Perempuan";
    const GENDERS = [
        "Laki-laki", "Perempuan"
    ];

    const RELIGIONS = [
        'Budha', 'Hindu', 'Katolik', 'Khonghucu', 'Islam', 'Protestan'
    ];

    protected $guarded = [];

    protected $casts = [
        'dob' => 'date:Y-m-d'
    ];

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class)->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    protected static function booted()
    {
        static::addGlobalScope(new StaffScope);
    }
}
