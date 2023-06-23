<?php

namespace App\Models;

use App\Models\User;
use App\Models\ExpenseDetail;
use App\Models\Scopes\ExpenseScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'sempoa_processed' => 'boolean',
        'expense_date' => 'date:Y-m-d',
        'expense_outgoing_date' => 'date:Y-m-d',
        'approval_at' => 'date:Y-m-d',
        'rejected_at' => 'date:Y-m-d',
    ];

    const STATUS_APPROVED   = "approved";
    const STATUS_PENDING    = "pending";
    const STATUS_REJECTED   = "rejected";
    const STATUS_OUTGOING    = "outgoing";
    const STATUS_DONE   = "done";
    const STATUS_DRAFT   = "draft";

    protected static function booted()
    {
        static::addGlobalScope(new ExpenseScope);
    }

    public function sempoas(): MorphMany
    {
        return $this->morphMany(Sempoa::class, 'sempoable');
    }

    public function expense_details(): HasMany
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function requested_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'request_by');
    }

    public function approved_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_by');
    }

    public function reject_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function getFilePhotoAttribute($value)
    {
        if (is_null($value)) return null;
        if (strpos($value, 'http') === false) {
            return Storage::url($value);
        } else {
            return $value;
        }
    }
}
