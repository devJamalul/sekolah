<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolProfileRequest;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session()->missing('school_id')) return to_route('home')->withToastError('Ups! Data sekolah belum aktif.');

        $data['school'] = School::find(session('school_id'));
        $data['title'] = "Informasi Sekolah";
        $data['grade_school'] =  School::GRADE_SCHOOL;

        return view('pages.school.profile.index', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolProfileRequest $request)
    {
        $school = School::findOrFail(session('school_id'));

        DB::beginTransaction();
        try {
            // school
            $school->school_name = $request->school_name;
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
                'action' => 'Ubah informasi sekolah',
                'user' => auth()->user()->name,
                'school' => $school->name
            ]);
            DB::rollback();
            return to_route('schools.profile-index')->withToastError('Ups, terjadi kesalahan saat mengubah informasi sekolah!');
        }

        return to_route('schools.profile-index')->withToastSuccess('Berhasil mengubah informasi sekolah!');
    }
}
