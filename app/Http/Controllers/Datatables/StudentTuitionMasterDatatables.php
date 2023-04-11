<?php

namespace App\Http\Controllers\Datatables;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentTuitionMaster;

class StudentTuitionMasterDatatables extends Controller
{
    public function index()
    {
        $studentTuitionMaster = StudentTuitionMaster::with('tuition.tuition_type')->latest('created_at');
        return DataTables::of($studentTuitionMaster)
                        ->editColumn('tuition_id', function ($data) {
                            return "test";
                        })
                        ->addColumn('action', function (Student $row) {
                            $data = [
                                'edit_url'     => route('tuition-master.edit', ['student' => $row->id]),
                                'delete_url'   => route('tuition-master.destroy', ['student' => $row->id]),
                                'redirect_url' => route('tuition-master.index'),
                                'resource'     => 'tuition-master',
                            ];
                            return view('components.datatable-action', $data);
                        })->toJson();
    }
}
