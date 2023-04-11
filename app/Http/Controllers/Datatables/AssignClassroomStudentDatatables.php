<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Classroom;
use Illuminate\Http\Request;
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
}
