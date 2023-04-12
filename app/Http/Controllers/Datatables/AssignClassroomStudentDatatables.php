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

        $studentClassroom = ClassroomStudent::whereHas('classroom.academic_year', fn ($q) => $q->active())->pluck('student_id');
        $students = Student::with('school')
            ->whereNotIn('id', $studentClassroom)->latest('created_at');
        return DataTables::of($students)->toJson();
    }
}
