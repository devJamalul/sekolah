<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use App\Notifications\NewSchoolPICNotification;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    private $title = "Pengguna";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = $this->title;
        return view('pages.users.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah {$this->title}";
        return view('pages.users.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        // cek sudah ada kepala sekolah atau belum
        $cek = User::query()
        ->role(User::ROLE_KEPALA_SEKOLAH)
        ->firstWhere([
            'school_id' => session('school_id'),
        ]);
        // lemparkan kembali jika sudah ada
        if ($cek) {
            return redirect()->route('users.index')->withToastError('Ups, kepala sekolah sudah ada. Tidak boleh ada dua kepala sekolah!');
        }

        DB::beginTransaction();
        try {
            $password = fake()->word();
            $user = new User();
            $user->school_id = session('school_id');
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($password);
            $user->new_password = 1;
            $user->email_verified_at = now();
            $user->save();

            $user->syncRoles(Role::firstWhere('name', $request->jabatan));

            $staff = new Staff();
            $staff->school_id = session('school_id');
            $staff->user_id = $user->getKey();
            $staff->name = $request->name;
            $staff->save();

            DB::commit();
            // event(new Registered($user)); # aktifkan jika sudah menggunakan email verifikasi
            $user->notify(new NewSchoolPICNotification($user, $password));
        } catch (Exception $th) {
            Log::error($th->getMessage(), [
                'action' => 'Tambah pengguna',
                'user' => auth()->user()->name,
                'sekolah' => School::find(session('school_id'))->name
            ]);
            DB::rollBack();
            return redirect()->route('users.index')->withToastError('Ups, terjadi kesalahan saat menambah data!');
        }

        return redirect()->route('users.index')->withToastSuccess('Berhasil menambah data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->school_id != session('school_id')) {
            abort(404);
        }

        $title = "Ubah {$this->title}";
        return view('pages.users.edit', compact('user', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        if ($user->school_id != session('school_id')) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $user->syncRoles(Role::firstWhere('name', $request->jabatan));

            $staff = $user->staff;
            $staff->name = $request->name;
            $staff->save();

            DB::commit();
            DB::afterCommit(function () use ($user) {
                // todo: jika email berubah, maka jalankan verifikasi email di bawah
                // event(new Registered($user)); # aktifkan jika sudah menggunakan email verifikasi
            });
        } catch (Exception $th) {
            Log::error($th->getMessage(), [
                'action' => 'Ubah pengguna',
                'user' => auth()->user()->name,
                'sekolah' => School::find(session('school_id'))->name
            ]);
            DB::rollBack();
            return redirect()->route('users.index')->withToastError('Ups, terjadi kesalahan saat mengubah data!');
        }

        return redirect()->route('users.index')->withToastSuccess('Berhasil mengubah data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->school_id != session('school_id')) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return response()->json([
                'msg' => 'Berhasil menghapus data pengguna!'
            ], 200);
        } catch (Exception $th) {
            Log::error($th->getMessage(), [
                'action' => 'Hapus pengguna',
                'user' => auth()->user()->name,
                'sekolah' => School::find(session('school_id'))->name
            ]);
            DB::rollback();
            return response()->json([
                'msg' => 'Ups gagal menghapus data pengguna!'
            ], 400);
        }
    }
}
