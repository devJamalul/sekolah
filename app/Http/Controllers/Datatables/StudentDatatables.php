<?php

namespace App\Http\Controllers\Datatables;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Str;

class StudentDatatables extends Controller
{
    public function index()
    {
        $students = Student::with('school')->latest('created_at');
        return DataTables::of($students)
                        ->editColumn('name', function ($data) {
                            return "<a href='" . route('students.show', $data->getKey()) . "'>".Str::of($data->name)->limit(20, '...')."</a>";
                        })
                        ->editColumn('gender', function ($data) {
                            return strtolower($data->gender) == Student::GENDER_LAKI ? 'Laki-Laki' : 'Perempuan';
                        })
                        ->editColumn('address', function ($data) {
                            return Str::of($data->address)->limit(40, '...');
                        })
                        ->addColumn('action', function (Student $row) {
                            $data = [
                                'edit_url'     => route('students.edit', ['student' => $row->id]),
                                'delete_url'   => route('students.destroy', ['student' => $row->id]),
                                'redirect_url' => route('students.index'),
                                'resource'     => 'students',
                                'custom_links' => [
                                    [
                                        'label' => 'Biaya Khusus',
                                        'url' => route('tuition-master.index', ['id' => $row->id]),
                                    ]
                                ]
                            ];
                            return view('components.datatable-action', $data);
                        })->rawColumns(['name'])
                        ->toJson();
    }
}
