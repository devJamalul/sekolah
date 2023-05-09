<?php

namespace App\Http\Controllers\Datatables;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentTuitionMaster;
use App\Models\Tuition;
use App\Models\User;
use Illuminate\Http\Request;

class TuitionApprovalDatatables extends Controller
{
    public function index(Request $request)
    {
        $tuitions = Tuition::where('school_id', session('school_id'))
                                ->with('tuition_type')
                                ->withTrashed()
                                ->get();
        
        return DataTables::of($tuitions)
                        ->addColumn('tuition_name', function ($row) {
                            return $row->tuition_type->name;
                        })
                        ->addColumn('price', function ($row) {
                            return 'Rp. ' . number_format($row->price, 0, ',', '.');
                        })
                        ->addColumn('status', function ($row) {
                            $createdBy = User::where('id', $row->request_by)->first() ?? '-';
                            return $createdBy?->name ?? '-';
                        })  
                        ->addColumn('created_by', function ($row) {
                            $createdBy = User::where('id', $row->request_by)->first() ?? '-';
                            return $createdBy?->name ?? '-';
                        })  
                        ->addColumn('approved_by', function ($row) {
                            $approvedBy = User::where('id', $row->approvel_by)->first();
                            return $approvedBy?->name ?? '-';
                        })
                        ->addColumn('action', function (Tuition $row) use($request) {
                            // $data = [
                            //     'edit_url'     => route('tuition-master.edit', ['id' => $request->id, 'tuition_master' => $row->student_tuition_masters_id]),
                            //     'delete_url'   => route('tuition-master.destroy', ['id' => $request->id, 'tuition_master' => $row->student_tuition_masters_id]),
                            //     'redirect_url' => route('tuition-master.index', ['id' => $request->id]),
                            //     'resource'     => 'tuition-master',
                            // ];
                            // return view('components.datatable-action', $data);
                        })->toJson();
    }
}
