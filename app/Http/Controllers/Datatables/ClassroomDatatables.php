<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ClassroomDatatables extends Controller
{
    //
    public function index()
    {
        $classroom = Classroom::with('school', 'grade', 'academic_year')->get();
        return DataTables::of($classroom)
            ->addColumn('action', function (Classroom $row) {
                $data = [
                    'edit_url'     => route('classroom.edit', ['classroom' => $row->id]),
                    'delete_url'   => route('classroom.destroy', ['classroom' => $row->id]),
                    'redirect_url' => route('classroom.index'),
                    'resource'     => 'classroom',
                ];
                return view('components.datatable-action', $data);
            })->toJson();
    }
}
