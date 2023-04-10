<?php

namespace App\Http\Controllers\Datatables;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TuitionType;

class StudentDatatables extends Controller
{
    public function index()
    {
        $students = Student::with('school');
        return DataTables::of($students)
            ->editColumn('gender', function ($data) {
                return strtolower($data->gender) == Student::GENDER_LAKI ? 'Laki-Laki' : 'Perempuan';
            })
            ->addColumn('action', function (Student $row) {
                $data = [
                    'edit_url'     => route('students.edit', ['student' => $row->id]),
                    'delete_url'   => route('students.destroy', ['student' => $row->id]),
                    'redirect_url' => route('students.index'),
                    'resource'     => 'students',
                ];
                return view('components.datatable-action', $data);
            })->toJson();
    }
}
