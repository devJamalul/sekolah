<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentTuitionMaster extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = [];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function tuition(): BelongsTo
    {
        return $this->belongsTo(Tuition::class);
    }

    public function tuitions(): BelongsToMany
    {
        return $this->belongsToMany(Tuition::class);
    }

}
