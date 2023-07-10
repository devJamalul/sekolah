<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use App\Notifications\NewSchoolPICNotification;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Throwable;

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
        if ($request->jabatan == User::ROLE_KEPALA_SEKOLAH) {
            $cek = User::query()
                ->role(User::ROLE_KEPALA_SEKOLAH)
                ->firstWhere([
                    'school_id' => session('school_id'),
                ]);
            // lemparkan kembali jika sudah ada
            if ($cek) {
                return redirect()->route('users.index')->withToastError('Ups, kepala sekolah sudah ada. Tidak boleh ada dua kepala sekolah!');
            }
        }

        // cek sudah ada admin sekolah atau belum
        if ($request->jabatan == User::ROLE_ADMIN_SEKOLAH) {
            $cek = User::query()
                ->role(User::ROLE_ADMIN_SEKOLAH)
                ->firstWhere([
                    'school_id' => session('school_id'),
                ]);
            // lemparkan kembali jika sudah ada
            if ($cek) {
                return redirect()->route('users.index')->withToastError('Ups, admin sekolah sudah ada. Tidak boleh ada dua admin sekolah!');
            }
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
        } catch (Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Tambah pengguna',
                'user' => auth()->user()->name,
                'sekolah' => School::find(session('school_id'))->name
            ]);
            DB::rollBack();
            return redirect()->route('users.create')->withInput()->withToastError('Ups, terjadi kesalahan saat menambah data! ' . $th->getMessage());
        }

        return redirect()->route('users.index')->withToastSuccess('Berhasil menambah data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $title = "Detail {$this->title}";
        return view('pages.users.detail', compact('title', 'user'));
    }

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

        $title = "Ubah {$this->title}";
        return view('pages.users.edit', compact('user', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
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

        // simpan role awalnya
        $role_sebelumnya = $user->getRoleNames()[0];

        // cek sudah ada kepala sekolah atau belum
        if ($request->jabatan == User::ROLE_KEPALA_SEKOLAH) {
            $cek = User::query()
                ->role(User::ROLE_KEPALA_SEKOLAH)
                ->where('id', '<>', $user->getKey())
                ->firstWhere([
                    'school_id' => session('school_id'),
                ]);
            // lemparkan kembali jika sudah ada
            if ($cek) {
                return redirect()->route('users.index')->withToastError('Ups, kepala sekolah sudah ada. Tidak boleh ada dua kepala sekolah!');
            }
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
            $user->refresh();

            // pengubahan info kepsek jika ada transisi posisi kepsek
            if (
                $role_sebelumnya != User::ROLE_KEPALA_SEKOLAH
                && $user->getRoleNames()[0] == User::ROLE_KEPALA_SEKOLAH
            ) {
                $school = School::find($user->school_id);
                $school->foundation_head_name = $user->name;
                $school->foundation_head_email = $user->email;
                $school->foundation_head_tlpn = $user->staff->phone_number;
                $school->save();
            }

            // pengubahan info kepsek jika ada status quo pada posisi kepsek
            if (
                $role_sebelumnya == User::ROLE_KEPALA_SEKOLAH
                && $user->getRoleNames()[0] != User::ROLE_KEPALA_SEKOLAH
            ) {
                $school = School::find($user->school_id);
                $school->foundation_head_name = null;
                $school->foundation_head_email = null;
                $school->foundation_head_tlpn = null;
                $school->save();
            }

        } catch (Throwable $th) {
            Log::error($th->getMessage(), [
                'action' => 'Ubah pengguna',
                'user' => auth()->user()->name,
                'sekolah' => School::find(session('school_id'))->name
            ]);
            DB::rollBack();
            return redirect()->route('users.edit', $user->getKey())->withInput()->withToastError('Ups, terjadi kesalahan saat mengubah data!');
        }

        return redirect()->route('users.index')->withToastSuccess('Berhasil mengubah data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->school_id != session('school_id')) {
            abort(403);
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
