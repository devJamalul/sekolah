<?php

namespace App\Http\Controllers\Reports;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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

        return view('pages.report.student.index', $data);
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
        $query = null;

        // Show Filtered By Academic Year / Grade if param exists
        if ($request->academic_year || $request->grade) {
            $query = Classroom::query()->with('students.classrooms.grade')->where('school_id', session('school_id'));

            // Filter By Academic Year (If Exist)
            $query->when($request->academic_year, function($query) use($request){
                $query->where('academic_year_id', $request->academic_year);
            });

            // Filter By Grade (If Exist)
            $query->when($request->grade, function($query) use($request){
                $query->where('grade_id', $request->grade);
            });

            // Filter By Classroom (If Exist)
            $query->when($request->classroom, function($query) use($request){
                $query->where('id', $request->classroom);
            });
            
            $classrooms = $query->get();

            $students = collect($classrooms)->map(function($classroom){
                if ($classroom->students) {
                    return collect($classroom)->map(function($student){
                        return $student;
                    });
                }
            })->pluck('students')->collapse();

        } else {
            $students = Student::where('school_id', session('school_id'))->get();
        }

        switch ($request->action) {
            case 'excel':
                return Excel::download(new StudentsExport($students), "data_murid_". Carbon::parse(now())->format('d-m-Y') .".xlsx");
                break;
            case 'pdf':
                return PDF::loadView('exports.report-students', compact('students'))->download("data_murid_". Carbon::parse(now())->format('d-m-Y') .".pdf");
                break;
        }
    }
}
