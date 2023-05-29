<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassroomStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "classroom_student";

    protected $fillable = [
        'classroom_id',
        'student_id'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
