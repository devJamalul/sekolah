<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserVerificationRequest;
use App\Models\User;
use App\Models\UserEmailVerification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($email, $token)
    {
        try {
            DB::beginTransaction();

            $user = User::firstWhere('email', $email);
            $verify = UserEmailVerification::firstWhere('email', $email);

            if (!$user or !$verify) {
                throw new Exception('Pengguna tidak ditemukan.');
            }

            if ($user->remember_token != $token) {
                throw new Exception('Token tidak ditemukan.');
            }

            $user->email_verified_at = now();
            $user->save();

            $verify->status = UserEmailVerification::STATUS_VERIFIED;
            $verify->save();

            $data['email'] = $email;
            $data['token'] = $token;

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), [
                'action' => '[GET] Verifikasi user',
                'user' => $user ?? null
            ]);

            return to_route('login')->withToastError('Ups! ' . $th->getMessage());
        }

        return view('pages.auth.set-password', $data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(UserVerificationRequest $request, $email, $token)
    {
        try {
            DB::beginTransaction();

            $user = User::firstWhere('email', $email);
            $verify = UserEmailVerification::firstWhere('email', $email);

            if (!$user or !$verify) {
                throw new Exception('Pengguna tidak ditemukan.');
            }

            if ($user->remember_token != $token) {
                throw new Exception('Token tidak ditemukan.');
            }

            $user->remember_token = null;
            $user->password = bcrypt($request->password);
            $user->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(), [
                'action' => '[POST] Verifikasi user',
                'user' => $user ?? null
            ]);

            return to_route('user-verification.index', [$email, $token])->withToastSuccess('Akun Anda telah siap!');
        }

        return to_route('login')->withToastSuccess('Akun Anda telah siap!');
    }
}
