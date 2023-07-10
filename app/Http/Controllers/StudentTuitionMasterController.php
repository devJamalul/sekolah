<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentTuitionMasterRequest;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentTuitionMaster;
use App\Models\Tuition;
use Illuminate\Http\Request;

class StudentTuitionMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $student = Student::findOrFail($req->id);
        $data = [
            'id' => $req->id,
            'title' => "Biaya Khusus $student->name"
        ];

        return view('pages.students.tuition-master.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $req)
    {
        $student = Student::findOrFail($req->id);
        $academicYear = AcademicYear::where('status_years', '!=', AcademicYear::STATUS_CLOSED)->first();
        $grades = [];
        $classNow = $student->classNow();
        if (!is_null($classNow)) {
            $grades[] = $classNow->grade_id;
        }
        $classNext = $student->classNext();
        if (!is_null($classNext)) {
            $grades[] = $classNext->grade_id;
        }

        // If no Academic Year data
        if (!$academicYear) return redirect()->back()->withToastError('Belum ada data tahun akademik');

        $selectedStudentTuitionMaster = StudentTuitionMaster::where('student_id', $student->id)->get();
        $studentTuitionMaster = collect(Tuition::with('tuition_type', 'grade')
            ->where('school_id', $student->school_id)
            ->where('academic_year_id', $academicYear->id)
            ->whereIn('grade_id', $grades)
            ->where('status', '=', Tuition::STATUS_APPROVED)
            ->get())
            ->reject(function ($tuitionMasters) use ($selectedStudentTuitionMaster) {
                foreach ($selectedStudentTuitionMaster as $selectedTuition) {

                    // Remove if has same id with SelectedStudentMasterTuition
                    if ($tuitionMasters->id == $selectedTuition->tuition_id) return $tuitionMasters;
                }
            });

        // If no Tuition data
        if (count($studentTuitionMaster) == 0) return redirect()->back()->withToastError('Belum ada data biaya');

        $data = [
            'id' => $req->id,
            'title' => "Tambah Biaya Khusus $student->name",
            'tuitions' => $studentTuitionMaster,
        ];

        return view('pages.students.tuition-master.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentTuitionMasterRequest $request)
    {
        try {
            $studentTuitionMaster                       = new StudentTuitionMaster;
            $studentTuitionMaster->student_id           = $request->id;
            $studentTuitionMaster->tuition_id           = $request->tuition_id;
            $studentTuitionMaster->price                = formatAngka($request->price);
            $studentTuitionMaster->note                 = $request->note;
            $studentTuitionMaster->save();
            return redirect()->route('tuition-master.index', ['id' => $request->id])->withToastSuccess('Berhasil menambahkan Biaya Khusus Siswa!');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->withToastError('Ops! ' . $th->getMessage());
        }
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
    public function edit(Request $request)
    {
        $student = Student::findOrFail($request->id);
        $academicYear = AcademicYear::where('status_years', '!=', AcademicYear::STATUS_CLOSED)->first();
        $grades = [];
        $classNow = $student->classNow();
        if (!is_null($classNow)) {
            $grades[] = $classNow->grade_id;
        }
        $classNext = $student->classNext();
        if (!is_null($classNext)) {
            $grades[] = $classNext->grade_id;
        }
        $studentTuitionMaster = StudentTuitionMaster::findOrFail($request->tuition_master);

        $selectedStudentTuitionMasterData = StudentTuitionMaster::where('student_id', $student->id)->get();
        $tuitions = Tuition::with('tuition_type')
            ->where('school_id', $student->school_id)
            ->whereIn('grade_id', $grades)
            ->where('status', '=', Tuition::STATUS_APPROVED)
            ->where('academic_year_id', $academicYear->id)
            ->get();
        $studentTuitionMasterData = collect($tuitions)->reject(function ($tuitionMasters) use ($selectedStudentTuitionMasterData) {
            foreach ($selectedStudentTuitionMasterData as $selectedTuition) {
                if ($tuitionMasters->id == $selectedTuition->tuition_id) return $tuitionMasters;
            }
        });
        $selectedStudentTuitionMaster = collect($tuitions)->first(function ($tuitionMasters) use ($studentTuitionMaster) {
            if ($tuitionMasters->id == $studentTuitionMaster->tuition_id) return $tuitionMasters;
        });

        $data = [
            'student_id' => $request->id,
            'title' => "Ubah Biaya Khusus $student->name",
            'tuitions' => $studentTuitionMasterData->merge([$selectedStudentTuitionMaster]),
            'current_tuition' => $studentTuitionMaster,
        ];

        return view('pages.students.tuition-master.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentTuitionMasterRequest $request)
    {
        try {
            $studentTuitionMaster = StudentTuitionMaster::findOrFail($request->tuition_master);
            $studentTuitionMaster->student_id           = $request->id;
            $studentTuitionMaster->tuition_id           = $request->tuition_id;
            $studentTuitionMaster->price                = formatAngka($request->price);
            $studentTuitionMaster->note                 = $request->note;
            $studentTuitionMaster->save();
            return redirect()->route('tuition-master.index', ['id' => $request->id])->withToastSuccess('Berhasil menambahkan Biaya Khusus Siswa!');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->withToastError('Ops, ada kesalahan saat menambahkan Biaya Khusus Siswa!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            StudentTuitionMaster::findOrFail($request->tuition_master)->delete();
            return response()->json([
                'msg' => 'Berhasil menghapus Biaya Khusus Siswa!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal menghapus Biaya Khusus Siswa!'
            ], 400);
        }
    }
}
