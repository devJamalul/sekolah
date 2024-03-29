<?php

namespace App\Http\Controllers\Datatables;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\StudentTuitionMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentTuitionMasterDatatables extends Controller
{
    public function index(Request $request)
    {
        $studentTuitionMaster = StudentTuitionMaster::select(['student_tuition_masters.id as student_tuition_masters_id', 'student_tuition_masters.*'])->where('student_id', $request->id)->with('tuition.tuition_type')->latest('student_tuition_masters.created_at');
        return DataTables::of($studentTuitionMaster)
                        ->editColumn('tuition_type', function ($row) {
                            return Str::of($row->tuition->tuition_type->name)->limit(20, '...');
                        })
                        ->editColumn('price', function ($row) {
                            return 'Rp. ' . number_format($row->price, 0, ',', '.');
                        })
                        ->editColumn('note', function ($row) {
                            return $row->note ? Str::of($row->note)->limit(20, '...') : '-';
                        })
                        ->addColumn('action', function (StudentTuitionMaster $row) use($request) {
                            $data = [
                                'edit_url'     => route('tuition-master.edit', ['id' => $request->id, 'tuition_master' => $row->student_tuition_masters_id]),
                                'delete_url'   => route('tuition-master.destroy', ['id' => $request->id, 'tuition_master' => $row->student_tuition_masters_id]),
                                'redirect_url' => route('tuition-master.index', ['id' => $request->id]),
                                'resource'     => 'tuition-master',
                            ];
                            return view('components.datatable-action', $data);
                        })->toJson();

                        
    }
}