<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\ClassroomStudent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class AssignClassroomStudentDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $classroom = Classroom::with('students')->has('students')->where('id', $request->classroom_id)->first();
        $students   = $classroom?->students ?? [];
        return DataTables::of($students)
            ->toJson();
    }

    public function students(Request $request)
    {

        $studentClassroom = ClassroomStudent::whereHas('classroom.academic_year', fn ($q) => $q->semua())->pluck('student_id');
        $students = Student::with('school')
            ->whereNotIn('id', $studentClassroom)
            ->when($request->has('nis'), function ($q) use ($request) {
                $q->where('nis', 'LIKE', '%' . $request->nis . '%');
            })
            ->when($request->has('name'), function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->name . '%');
            })
            ->when($request->has('dob'), function ($q) use ($request) {
                $q->where('dob', 'LIKE', '%' . $request->dob . '%');
            })
            ->latest('created_at');
        return DataTables::of($students)->toJson();
    }
}
