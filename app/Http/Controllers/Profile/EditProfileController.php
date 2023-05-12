<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditProfileController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $data['title'] = 'Ubah Profil';
        $data['user'] = User::with('staff')->find(auth()->id());

        return view('pages.users.profile', $data);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UserProfileRequest $request)
    {
        $user = User::with('staff')->find(auth()->id());
        DB::beginTransaction();
        try {
            // users
            $user->name = $request->name;
            $user->email = $request->email;

            // staff
            if ($request->has('biodata')) {
                $user->staff->gender = $request->gender;
                $user->staff->religion = $request->religion;
                $user->staff->dob = $request->dob;
                $user->staff->phone_number = $request->phone_number;
                $user->staff->nik = $request->nik;
                $user->staff->nip = $request->nip;
                $user->staff->nidn = $request->nidn;
            }

            $user->push();
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Ubah profil',
                'user' => auth()->user()->name,
                'data' => $request->all()
            ]);
            DB::rollBack();
            return to_route('edit-profile.show')->withToastError('Ups! Profil gagal diubah!');
        }
        return to_route('edit-profile.show')->withToastSuccess('Profil berhasil diubah!');
    }
}
