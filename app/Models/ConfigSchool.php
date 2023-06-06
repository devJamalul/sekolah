<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfigSchool extends Model
{
    use HasFactory;
    protected $fillable = ['school_id', 'config_id'];

    public function config(): BelongsTo
    {
        return $this->belongsTo(Config::class);
    }
}
