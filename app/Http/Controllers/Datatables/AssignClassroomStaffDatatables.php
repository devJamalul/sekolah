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
        $classroom = ClassroomStaff::with('staff', 'classroom.grade')->get();
        return DataTables::of($classroom)
            ->addColumn('class_staff', function ($row) {
                return $row->classroom->grade->grade_name . ' - ' . $row->classroom->name;
            })
            ->addColumn('action', function ($row) {
                return 'Aksi';
            })
            ->toJson();
    }

    public function staffs(Request $request)
    {
        $staff = Staff::with('school')->latest('created_at');
        return DataTables::of($staff)->toJson();
    }
}
