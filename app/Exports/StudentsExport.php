<?php

namespace App\Exports;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsExport implements FromView
{
    function __construct(protected $request)
    {
    }

    public function view(): View
    {
        $request = $this->request;

        $query = Classroom::query()->with('students')->where('school_id', session('school_id'));

        $query->when($request->academic_year, function($query) use($request){
            $query->where('academic_year_id', $request->academic_year);
        });

        $query->when($request->grade, function($query) use($request){
            $query->where('grade_id', $request->grade);
        });

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

        
        dump($classrooms);
        dd($students);

        return view('exports.report-students-excel', compact('students'));
    }
}
