<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPasswordRequest;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditPasswordController extends Controller
{

    /**
     * Display the resource.
     */
    public function show()
    {
        $data['title'] = 'Ubah Password';
        $data['user'] = User::find(auth()->id());

        return view('pages.users.password', $data);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UserPasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find(auth()->id());
            $user->password = bcrypt($request->password);
            $user->new_password = false;
            $user->save();
            $user->notify(new PasswordResetNotification($user));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('edit-password.show')->withToastError($th->getMessage());
        }

        return to_route('edit-password.show')->withToastSuccess('Password berhasil diubah!');
    }
}
