<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmailVerification extends Model
{
    use HasFactory;

    const STATUS_UNVERIFIED = 'unverified';
    const STATUS_VERIFIED = 'verified';
}
