<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grade;
use App\Models\School;
use App\Models\Tuition;
use App\Models\TuitionType;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TuitionRequest;

class TuitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = "Biaya";
        return view('pages.tuition.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = "Tambah Biaya";
        $tuitionTypes = TuitionType::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('academic_year_name')->get();
        $grades = Grade::orderBy('grade_name')->get();
        $users = User::where('school_id', session('school_id'))->whereHas('roles', function ($q) {
            $q->whereIn('name', [
                User::ROLE_ADMIN_SEKOLAH,
                User::ROLE_ADMIN_YAYASAN,
                User::ROLE_TATA_USAHA,
                User::ROLE_BENDAHARA,
                User::ROLE_KEPALA_SEKOLAH,
            ]);
        })->get();
        return view('pages.tuition.create', compact('tuitionTypes', 'academicYears', 'grades', 'title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TuitionRequest $request)
    {
        DB::beginTransaction();
        try {

            $tuition                    = new Tuition();
            $tuition->school_id         = session('school_id');
            $tuition->tuition_type_id   = $request->tuition_type_id;
            $tuition->academic_year_id  = $request->academic_year_id;
            $tuition->grade_id          = $request->grade_id;
            $tuition->price             = $request->price;
            $tuition->request_by        = $request->requested_by;
            $tuition->approval_by       = $request->approved_by;
            $tuition->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tuition.index')->withToastError('Eror Simpan Biaya!');
        }

        return redirect()->route('tuition.index')->withToastSuccess('Berhasil Simpan Biaya!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tuition $tuition)
    {
        $title = 'Ubah Biaya';
        $tuitionTypes = TuitionType::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('academic_year_name')->get();
        $grades = Grade::orderBy('grade_name')->get();
        $users = User::where('school_id', session('school_id'))->whereHas('roles', function ($q) {
            $q->whereIn('name', [
                User::ROLE_ADMIN_SEKOLAH,
                User::ROLE_ADMIN_YAYASAN,
                User::ROLE_TATA_USAHA,
                User::ROLE_BENDAHARA,
                User::ROLE_KEPALA_SEKOLAH,
            ]);
        })->get();
        return view('pages.tuition.edit', compact('tuitionTypes', 'tuition', 'academicYears', 'grades', 'title', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TuitionRequest $request, Tuition $tuition)
    {
        //
        DB::beginTransaction();
        try {

            $tuition->school_id             = session('school_id');
            $tuition->tuition_type_id       = $request->tuition_type_id;
            $tuition->academic_year_id      = $request->academic_year_id;
            $tuition->grade_id              = $request->grade_id;
            $tuition->price                 = $request->price;
            $tuition->request_by            = $request->requested_by;
            $tuition->approval_by           = $request->approved_by;
            $tuition->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tuition.index')->withToastError('Eror Simpan Biaya!');
        }

        return redirect()->route('tuition.index')->withToastSuccess('Berhasil Simpan Biaya!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tuition $tuition)
    {
        DB::beginTransaction();
        try {

            $tuition->delete();
            DB::commit();

            return response()->json([
                'msg' => 'Berhasil Hapus Biaya!'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Eror Hapus Biaya!'
            ]);
        }
    }
}
