<?php

namespace App\Models;

use App\Models\Type;
use App\Models\School;
use App\Models\Scopes\StudentTuitionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentTuition extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = "pending";
    const STATUS_PAID = "paid";
    const STATUS_PARTIAL = "partial";

    protected $guarded = [];

    protected $casts = [
        'period' => 'datetime:Y-m-d',
        'is_sent' => 'boolean'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // public function tuition_type(): BelongsTo
    // {
    //     return $this->belongsTo(TuitionType::class);
    // }

    public function payment_type(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student_tuition_details(): HasMany
    {
        return $this->hasMany(StudentTuitionDetail::class);
    }


    protected static function booted()
    {
        static::addGlobalScope(new StudentTuitionScope);
    }
}
