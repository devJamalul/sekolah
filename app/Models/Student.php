<?php

namespace App\Models;

use App\Models\User;
use App\Models\School;
use App\Models\Scopes\StudentScope;
use App\Models\StudentTuition;

use Carbon\Carbon;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    const GENDER_LAKI = 'l';
    const GENDER_PEREMPUAN = 'p';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_GRADUATE = 'graduate';

    protected $guarded = [];

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class)->withTimestamps();
    }

    public function student_tuitions(): HasMany
    {
        return $this->hasMany(StudentTuition::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student_tuition_masters(): HasMany
    {
        return $this->hasMany(School::class);
    }


    protected static function booted()
    {
        static::addGlobalScope(new StudentScope);
    }

    // Accessor
    public function getFilePhotoAttribute($value)
    {
        if (is_null($value)) return null;
        if (strpos($value, 'http') === false) {
            return Storage::url($value);
        } else {
            return $value;
        }
    }

    public function getFileBirthCertificateAttribute($value)
    {
        if (is_null($value)) return null;
        if (strpos($value, 'http') === false) {
            return Storage::url($value);
        } else {
            return $value;
        }
    }

    public function getFileFamilyCardAttribute($value)
    {
        if (is_null($value)) return null;
        if (strpos($value, 'http') === false) {
            return Storage::url($value);
        } else {
            return $value;
        }
    }
    // End Accessor


    public static function boot()
    {
        parent::boot();

        self::created(function (Student $student) {

            // Save User
            $user               = new User;
            $user->school_id    = $student->school_id;
            $user->name         = $student->name;
            $user->email        = Str::slug($student->name . Carbon::parse($student->created_at)->format('dmy'), '-') . '@gmail.com';
            $user->password     = bcrypt('password');
            $user->save();
            $user->assignRole(User::ROLE_SISWA);
            // End Save User

            // Update Student's User ID
            $student->user_id   = $user->id;
            $student->save();
            // End Update Student's User ID

        });

        self::updated(function (Student $student) {

            // Update User
            $user               = User::findOrFail($student->user_id);
            $user->name         = $student->name;
            $user->save();
            // End Update User

        });

        self::deleted(function (Student $student) {

            // Delete User
            // User::findOrFail($student->user_id)->delete();
            // End Delete User

        });
    }
}
