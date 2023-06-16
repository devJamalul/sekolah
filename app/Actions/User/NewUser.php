<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\UserEmailVerification;
use App\Notifications\NewSchoolPICNotification;
use App\Notifications\NewUser as NotificationsNewUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class NewUser
{
    public static function createTokenFor(User $user): void
    {
        try {
            DB::beginTransaction();
            // pembuatan token
            $token = Password::getRepository()->create($user);
            $user->password = $token . date('YHmids');
            $user->remember_token = $token;
            $user->save();
            // pembuatan verifikasi email
            $verify = new UserEmailVerification();
            $verify->name = $user->name;
            $verify->email = $user->email;
            $verify->status = UserEmailVerification::STATUS_UNVERIFIED;
            $verify->save();
            // notifikasi
            $user->notify(new NotificationsNewUser($user));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
