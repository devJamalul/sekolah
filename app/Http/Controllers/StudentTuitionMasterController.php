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
        $test = StudentTuitionMaster::with('tuition.tuition_type')->latest('created_at')->get();
        // dd($test[0]->tuition->tuition_type->name);

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
        $academicYear = AcademicYear::where('status_years', 'started')->first();
        $selectedStudentTuitionMaster = Tuition::where('school_id', $student->school_id)->where('academic_year_id', $academicYear->id)->get();
        $studentTuitionMaster = collect(StudentTuitionMaster::with('tuition.tuition_type')->where('student_id', $student->id)->get())
                                ->reject(function($tuitionMasters) use($selectedStudentTuitionMaster){
                                    foreach ($selectedStudentTuitionMaster as $selectedTuition) {

                                        // Remove if has same id with SelectedStudentMasterTuition
                                        if ($tuitionMasters->id == $selectedTuition->tuition_id) return $tuitionMasters; 

                                    }
                                });

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
            $studentTuitionMaster->price                = $request->price;
            $studentTuitionMaster->note                 = $request->note;
            $studentTuitionMaster->save();
            return redirect()->route('tuition-master.index', ['id' => $request->id])->withToastSuccess('Berhasil menambahkan Biaya Khusus Siswa!');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->withToastError('Ops, ada kesalahan saat menambahkan Biaya Khusus Siswa!');
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
        $academicYear = AcademicYear::where('status_years', 'started')->first();
        $studentTuitionMaster = StudentTuitionMaster::findOrFail($request->tuition_master);

        $selectedStudentTuitionMaster = Tuition::where('school_id', $student->school_id)->where('academic_year_id', $academicYear->id)->get();
        $studentTuitionMasterData = collect(StudentTuitionMaster::with('tuition.tuition_type')->where('student_id', $student->id)->get())
                                ->reject(function($tuitionMasters) use($selectedStudentTuitionMaster, $request){
                                    foreach ($selectedStudentTuitionMaster as $selectedTuition) {

                                        // Remove if has same id with SelectedStudentMasterTuition
                                        if ($tuitionMasters->id == $selectedTuition->id && $tuitionMasters->id == $request->tuition_master ) return $tuitionMasters; 

                                    }
                                });
        // dd($studentTuitionMasterData);

        $data = [
            'student_id' => $request->id,
            'title' => "Ubah Biaya Khusus $student->name",
            'tuitions' => $studentTuitionMasterData,
            'current_tuition' => $studentTuitionMaster,
        ];

        return view('pages.students.tuition-master.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
