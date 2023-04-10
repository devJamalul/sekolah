<?php

namespace App\Models;

use App\Models\User;
use App\Models\Grade;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Tuition;
use App\Models\Classroom;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\TuitionType;
use App\Models\AcademicYear;
use App\Models\ClassroomStaff;
use App\Models\StudentTuition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class School extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_YAYASAN = "yayasan";
    const TYPE_SEKOLAH = "sekolah";

    const GRADE_SCHOOL  = ["TK", "SD", "SMP", "SMA", "SMK"];

    protected $guarded = [];



    public function academic_years(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function payment_types(): HasMany
    {
        return $this->hasMany(PaymentType::class);
    }

    public function staf(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function student_tuitions(): HasMany
    {
        return $this->hasMany(StudentTuition::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function tuitions(): HasMany
    {
        return $this->hasMany(Tuition::class);
    }

    public function tuition_types(): HasMany
    {
        return $this->hasMany(TuitionType::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(School::class, 'school_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeInduk($query)
    {
        return $query->whereNull('school_id');
    }
}
