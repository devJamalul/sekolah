<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserResetPasswordRequest;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangeUserPasswordController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->school_id != session('school_id')) {
            abort(403);
        }

        $admin = User::find(auth()->id());
        if (
            $user->getRoleNames()[0] == User::ROLE_ADMIN_SEKOLAH
            && $user->getKey() != Auth::id()
            && $admin->getRoleNames()[0] != User::ROLE_SUPER_ADMIN
            && $admin->getRoleNames()[0] != User::ROLE_OPS_ADMIN
        ) {
            return redirect()->route('users.index')->withToastError('Ups, anda tidak berhak mengubah data admin sekolah!');
        }

        $title = 'Reset Password';
        return view('pages.users.reset-password', compact('user', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserResetPasswordRequest $request, User $user)
    {
        if ($user->school_id != session('school_id')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $user->password = bcrypt($request->pasword);
            $user->save();
            $user->notify(new PasswordResetNotification($user));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('user-password.update', $user->getKey())->withToastError("Ops! Gagal reset password! " . $th->getMessage());
        }

        return redirect()->route('users.index')->withToastSuccess("Reset password Berhasil!");
    }
}
