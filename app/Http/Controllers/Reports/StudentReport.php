<?php

namespace App\Http\Controllers\Reports;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Excel;

class StudentReport extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::where('school_id', session('school_id'))->get();
        $grades = Grade::where('school_id', session('school_id'))->get();

        $data = [
            'academic_years' => $academicYears,
            'grades' => $grades,
            'title' => "Ekspor Data Siswa",
        ];

        return view('pages.students.export', $data);
    }

    public function getClassroomByFilter(Request $request)
    {
        $getClassrooms = Classroom::query()->where('school_id', session('school_id'));

        $getClassrooms->when($request->academic_year, function($query) use($request){
            $query->where('academic_year_id', $request->academic_year);
        });

        $getClassrooms->when($request->grade, function($query) use($request){
            $query->where('grade_id', $request->grade);
        });

        $classrooms = $getClassrooms->get();

        return response(["classrooms" => $classrooms, 'asdsad' => $request->academic_year]);
    }

    public function exportStudentReport(Request $request)
    {
        switch ($request->action) {
            case 'excel':
                return Excel::download(new StudentsExport($request), 'students.xlsx');
                break;
            case 'pdf':
                dd("Export to PDF");
                break;
        }
    }
}
