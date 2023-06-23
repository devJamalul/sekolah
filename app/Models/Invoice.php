<?php

namespace App\Models;

use App\Models\Scopes\InvoiceScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = "belum lunas";
    const STATUS_PAID = "lunas";
    const STATUS_PARTIAL = "partial";

    const POSTED_DRAFT = "draft";
    const POSTED_PUBLISHED = "terbit";
    const POSTED_SENT = "terkirim";

    const VOID = "void";

    protected $guarded = [];

    protected $with = [
        'invoice_details.wallet'
    ];

    protected $casts = [
        'sempoa_processed' => 'boolean',
        'is_original' => 'boolean',
        'invoice_date' => 'datetime:Y-m-d',
        'due_date' => 'datetime:Y-m-d',
        'price' => 'integer'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new InvoiceScope);
    }

    public function sempoas(): MorphMany
    {
        return $this->morphMany(Sempoa::class, 'sempoable');
    }

    public function invoice_details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}
