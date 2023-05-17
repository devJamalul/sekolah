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
        $staff = Staff::with('classrooms.grade', 'classrooms.academic_year')->latest('created_at');
        return DataTables::of($staff)
            ->addColumn('staff_class', function (Staff $row) {
                if ($row->classrooms->count() > 1) {
                    return '<span  class="btn btn-success btn-sm" onclick="modalDetailClassroom(' . $row->id . ')"> Lebih dari ' . $row->classrooms->count() - 1 . '</span>';
                } else {
                    $classroom = $row->classrooms?->first();
                    return $classroom ? $classroom->grade->grade_name . "-" . $classroom->name : 'belum ditentukan';
                }
            })
            ->addColumn('academic_year_name', function (Staff $row) {
                $classroom = $row->classrooms?->first();
                return $classroom ? $classroom->academic_year->academic_year_name : 'belum ditentukan';
            })
            ->addColumn('action', function (Staff $row) {
                $classroom = $row->classrooms?->first();
                return view('pages.assign-classroom-staff.action', compact('row', 'classroom'));
            })
            ->rawColumns(['staff_class'])
            ->toJson();
    }

    public function staffs(Request $request)
    {
        $staff = Staff::with('school')->latest('created_at');
        return DataTables::of($staff)->toJson();
    }
}
