<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
        $user = User::find(auth()->id());
        $user->password = bcrypt($request->password);
        $user->save();

        return to_route('edit-password.show')->withToastSuccess('Password berhasil diubah!');
    }
}
