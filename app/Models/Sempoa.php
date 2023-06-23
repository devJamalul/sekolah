<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Sempoa extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sempoable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'sempoable_type', 'sempoable_id');
    }
}
