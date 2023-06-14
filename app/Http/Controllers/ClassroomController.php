<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\School;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = "Kelas";
        return view('pages.classroom.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = "Tambah Kelas";
        $schools = School::all();
        $academicYears = AcademicYear::whereIn('status_years', [
            AcademicYear::STATUS_STARTED,
            AcademicYear::STATUS_REGISTRATION
        ])->orderBy('status_years', 'desc')->get();
        $grades = Grade::all();
        return view('pages.classroom.create', compact('schools', 'academicYears',  'grades', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassroomRequest $request)
    {

        DB::beginTransaction();

        try {
            $classes = explode(",", $request->name);

            foreach ($classes as $item) {
                $item = trim($item);
                $cek = Classroom::firstWhere([
                    'school_id' => session('school_id'),
                    'grade_id' => $request->grade_id,
                    'academic_year_id' => $request->academic_year_id,
                    'name' => $item
                ]);
                if (!$item or $item == '' or is_null($item) or $cek) continue;

                $classroom                      = new Classroom();
                $classroom->school_id           = session('school_id');
                $classroom->academic_year_id    = $request->academic_year_id;
                $classroom->grade_id            = $request->grade_id;
                $classroom->name                = $item;
                $classroom->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('classroom.create')->withInput()->withToastError('Eror Simpan Kelas. ' . $th->getMessage());
        }

        return redirect()->route('classroom.index')->withToastSuccess('Berhasil Simpan Kelas');
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
    public function edit(Classroom $classroom)
    {
        $schools = School::all();
        $grades = Grade::all();
        $academicYears = AcademicYear::whereIn('status_years', [
            AcademicYear::STATUS_STARTED,
            AcademicYear::STATUS_REGISTRATION
        ])->orderBy('status_years', 'desc')->get();
        $title = "Ubah Kelas";
        return view('pages.classroom.edit', compact('schools', 'academicYears',  'grades', 'classroom', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassroomRequest $request, Classroom $classroom)
    {

        DB::beginTransaction();

        try {
            if (str_contains($request->name, ',')) {
                throw new \Exception('Tidak boleh mengandung koma.');
            }

            $classroom->school_id           = session('school_id');
            $classroom->academic_year_id    = $request->academic_year_id;
            $classroom->grade_id            = $request->grade_id;
            $classroom->name                = $request->name;
            $classroom->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('classroom.index')->withToastError('Eror Simpan Kelas!');
        }

        return redirect()->route('classroom.index')->withToastSuccess('Berhasil Simpan Kelas!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        DB::beginTransaction();
        try {

            $classroom->delete();
            DB::commit();

            return response()->json([
                'msg' => 'Berhasil Hapus Kelas!'
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response()->json([
                'msg' => 'Eror Hapus Kelas!'
            ]);
        }
    }
}
