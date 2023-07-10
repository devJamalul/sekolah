<?php

namespace App\Http\Controllers\School;

use Excel;
use Exception;
use App\Models\User;
use App\Models\Staff;
use App\Models\School;
use App\Imports\AllImport;
use Illuminate\Http\Request;
use App\Actions\User\NewUser;
use App\Jobs\ImportSekolahJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Illuminate\Validation\ValidationException;
use App\Notifications\NewSchoolPICNotification;

class SchoolsController extends Controller
{
    protected $title = "Sekolah";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = $this->title;
        return view('pages.school.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Sekolah";
        $grade_school =  School::GRADE_SCHOOL;
        return view('pages.school.create', compact('title', 'grade_school'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SchoolRequest $request)
    {

        // $role = $request->has('school_id') ? User::ROLE_ADMIN_SEKOLAH : $role = User::ROLE_ADMIN_YAYASAN;
        $role = User::ROLE_ADMIN_SEKOLAH;

        DB::beginTransaction();
        try {
            // sekolah
            $school = new School();
            $school->school_name = $request->school_name;
            // $school->school_id = $request->school_id;
            $school->province = $request->province;
            $school->city = $request->city;
            $school->postal_code = $request->postal_code;
            $school->address = $request->address;
            $school->grade = $request->grade;
            $school->email = $request->email;
            $school->phone = $request->phone;
            $school->foundation_head_name = $request->foundation_head_name;
            $school->foundation_head_email = $request->foundation_head_email;
            $school->foundation_head_tlpn = $request->foundation_head_tlpn;
            $school->save();

            // Admin Sekolah
            $password = fake()->word();
            $user = new User();
            $user->school_id = $school->getKey();
            $user->name = $request->name_pic;
            $user->email = $request->email_pic;
            $user->password = bcrypt($password);
            $user->new_password = true;
            $user->save();
            // integrasi tabel staff
            $staff = new Staff();
            $staff->school_id = $school->getKey();
            $staff->user_id = $user->id;
            $staff->name = $user->name;
            $staff->save();
            // assign Admin Sekolah
            $school->staff_id = $staff->getKey();
            $school->save();
            // Admin Sekolah assign role
            $user->assignRole($role);

            // Kepala Sekolah
            $password2 = fake()->word();
            $kepsek = new User();
            $kepsek->school_id = $school->getKey();
            $kepsek->name = $request->foundation_head_name;
            $kepsek->email = $request->foundation_head_email;
            $kepsek->password = bcrypt($password2);
            $kepsek->new_password = true;
            $kepsek->save();
            // integrasi tabel staff
            $staff = new Staff();
            $staff->school_id = $school->getKey();
            $staff->user_id = $kepsek->id;
            $staff->name = $kepsek->name;
            $staff->phone_number = $request->foundation_head_tlpn;
            $staff->save();
            // Kepala Sekolah assign role
            $kepsek->assignRole(User::ROLE_KEPALA_SEKOLAH);

            // verification & notification
            NewUser::createTokenFor($user);
            NewUser::createTokenFor($kepsek);

            DB::commit();
        } catch (Exception $th) {
            Log::error($th->getMessage(), [
                'action' => 'Tambah sekolah',
                'user' => auth()->user()->name,
                'sekolah' => $school->name
            ]);
            DB::rollback();
            return to_route('schools.create')->withInput()->withToastError('Ups, terjadi kesalahan saat menambah data! ' . $th->getMessage());
        }

        return to_route('schools.index')->withToastSuccess('Berhasil menambah data!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        $school->load('staf.user');
        $title = "Ubah Sekolah";
        $grade_school =  School::GRADE_SCHOOL;
        return view('pages.school.edit', compact('school', 'title', 'grade_school'));
    }

    /**
     *For detail school
     */

    public function show(School $school)
    {
        $school->load('staf.user');
        $title = "Detail Sekolah " . $school->school_name;
        return view('pages.school.detail', compact('school', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolRequest $request, School $school)
    {
        DB::beginTransaction();
        try {
            // school
            $school->school_name = $request->school_name;
            // $school->school_id = $request->school_id;
            $school->province = $request->province;
            $school->city = $request->city;
            $school->postal_code = $request->postal_code;
            $school->address = $request->address;
            $school->grade = $request->grade;
            $school->email = $request->email;
            $school->phone = $request->phone;
            $school->save();

            DB::commit();
        } catch (Exception $th) {
            Log::error($th->getMessage(), [
                'action' => 'Ubah sekolah',
                'user' => auth()->user()->name,
                'school' => $school->name
            ]);
            DB::rollback();
            return to_route('schools.index')->withToastError('Ups, terjadi kesalahan saat mengubah data! ' . $th->getMessage());
        }

        return to_route('schools.index')->withToastSuccess('Berhasil mengubah data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        DB::beginTransaction();
        try {
            $school->delete();
            DB::commit();
            return response()->json([
                'msg' => 'Berhasil menghapus data sekolah!'
            ], 200);
        } catch (Exception $th) {
            Log::error($th->getMessage(), [
                'action' => 'Hapus sekolah',
                'user' => auth()->user()->name,
                'sekolah' => $school->name
            ]);
            DB::rollback();
            return response()->json([
                'msg' => 'Ups gagal menghapus data sekolah! ' . $th->getMessage()
            ], 400);
        }
    }


    public function importSchool()
    {
        $data = [
            'title' => "Impor Data Semua",
        ];

        return view('pages.school.import', $data);
    }

    public function importAllByExcel(Request $request)
    {
        try {
            // Excel::import(new AllImport, $request->file('excel_file'));
            if($request->hasFile('excel_file')){
                $file = $request->file('excel_file');
                $filePath = Storage::putFileAs('school_import', $file, $file->hashName());

                ImportSekolahJob::dispatch($filePath)->delay(now()->addSeconds(5));
            }
                // Generate a URL for accessing the uploaded file

            return redirect()->route('schools.index')->withToastSuccess('Berhasil mengimpor data all!');
        } catch (ValidationException $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError($ex->errors());
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError("Ups! " . $ex->getMessage());
        }
    }
}
