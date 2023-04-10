<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\School;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Tuition;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    const ROLE_SUPER_ADMIN = "super admin";
    const ROLE_OPS_ADMIN = "ops admin";
    const ROLE_ADMIN_SEKOLAH = "admin sekolah";
    const ROLE_ADMIN_YAYASAN = "admin yayasan";
    const ROLE_TATA_USAHA = "tata usaha";
    const ROLE_BENDAHARA = "bendahara";
    const ROLE_KEPALA_SEKOLAH = "kepala sekolah";
    const ROLE_SISWA = "siswa";
    const ROLE_ALUMNI = "alumni";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $append = [
        'role'
    ];

    protected function jabatan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $user = User::find($this->id);
                return $user->getRoleNames()[0];
            }
        );
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function tuition(): HasMany
    {
        return $this->hasMany(Tuition::class);
    }
}
