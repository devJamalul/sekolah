<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AssignClassroomStudentRequest;
use App\Models\ClassroomStaff;
use App\Models\ClassroomStudent;

class AssignClassroomStudentController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data['title'] = "Penetapan Kelas";
        $data['academy_years'] = AcademicYear::semua()->orderBy('status_years', 'desc')->get();

        $data['academy_year'] = (object)[
            'started' => AcademicYear::active()->first(),
            'register' => AcademicYear::PPDB()->first(),
        ];
        $data['selected_academy_years'] = $request->academic_year;

        return view('pages.assign-classroom-student.index', $data);
    }

    public function store(AssignClassroomStudentRequest $request)
    {
        DB::beginTransaction();


        try {
            $this->setClassroom($request);
            DB::commit();
        } catch (\Throwable $th) {
            return redirect()
                ->route('assign-classroom-student.index', ['academic_year' => $request->academy_years])
                ->with('classroom_id', $request->classroom_id)
                ->withToastError('Gagal Tetapkan Kelas');
        }


        return redirect()
            ->route('assign-classroom-student.index', ['academic_year' => $request->academy_years])
            ->withToastSuccess('Berhasil Tetapkan Kelas');
    }

    public function destroy(AssignClassroomStudentRequest $request)
    {

        DB::beginTransaction();
        try {
            $isChangeClassroom = $request->type == 'Pindah Kelas';

            if ($isChangeClassroom) {
                $this->destroyClassroom($request);
            }
            $classroom = $this->setClassroom($request);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->route('assign-classroom-student.index', ['academic_year' => $request->academic_year])
                ->with('classroom_id', $request->classroom_id)
                ->withToastError('Gagal ' . $request->type);
        }

        return redirect()
            ->route('assign-classroom-student.index', ['academic_year' => $request->academic_year])
            ->with('classroom_id', $request->classroom_id)
            ->withToastSuccess('Berhasil ' . $request->type);
    }

    public function classroom(Request $request)
    {

        try {
            if ($request->has('academy_year_id')) {
                $academicYear = $request->academy_year_id;
                $classroomStaff   = ClassroomStaff::groupBy('classroom_id')->pluck('classroom_id');
                $classroom    = Classroom::with('grade', 'staff')
                    ->when($request->has('get'), function ($row) use ($classroomStaff) {
                        $row->whereNotIn('id', $classroomStaff);
                    })
                    ->where('academic_year_id', $academicYear)->get();

                return response()->json(['msg' => 'Berhasil', 'classrooms' => $classroom], 200);
            } else {
                return response()->json(['msg' => 'Gagal', 'classrooms' => []], 201);
            }
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Gagal'], 400);
        }
    }

    private function setClassroom($request)
    {
        $classroom = Classroom::find($request->classroom_id);
        $classroom->students()->syncWithoutDetaching($request->id);
        $classroom->save();
        return $classroom;
    }

    private function destroyClassroom($request)
    {
        $classroom = Classroom::find($request->classroom_old);
        $classroom->students()->detach($request->id);
        $classroom->save();
        return $classroom;
    }
}
