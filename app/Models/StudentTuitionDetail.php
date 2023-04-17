<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Tuition;

class StudentTuitionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function tuitions(): BelongsToMany
    {
        return $this->belongsToMany(Tuition::class);
    }

    public function student_tuitions(): BelongsToMany
    {
        return $this->belongsToMany(Tuition::class);
    }

    public function students(): BelongsTo
    {
        return $this->belongsTo(Tuition::class, 'tuition_id');
    }

    public function tuition(): BelongsTo
    {
        return $this->belongsTo(Tuition::class, 'tuition_id');
    }
}
