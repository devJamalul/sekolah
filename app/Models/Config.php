<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public function scopeActive(Builder $query)
    {
        $query->where('is_enabled', true);
    }
}
