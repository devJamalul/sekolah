<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\ClassroomStaff;
use App\Models\Staff;

class AssignClassroomStaffDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $classroom = Classroom::with('staff')->has('staff')->where('id', $request->classroom_id)->first();
        $staff   = $classroom?->staff ?? [];
        return DataTables::of($staff)
            ->toJson();
    }

    public function staffs(Request $request)
    {
        $staff = Staff::with('school')->latest('created_at');
        return DataTables::of($staff)->toJson();
    }
}
