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
                            if ($row->deleted_at) 
                                return '<span class="badge badge-danger">Ditolak</span>';

                            if ($row->approval_by)
                                return '<span class="badge badge-success">Disetujui</span>';
                            
                            return '<span class="badge badge-primary">Menunggu Persetujuan</span>';
                        })  
                        ->addColumn('action', function (Tuition $row) use($request) {
                            $data = [
                                'redirect_url' => route('tuition-approval.index'),
                                'resource'     => 'tuition-approval',
                                'custom_links' => [
                                    [
                                        'label' => 'Detil',
                                        'url' => route('tuition-approval.show', ['tuition_approval' => $row->getKey()]),
                                    ]
                                ]
                            ];
                            return view('components.datatable-action', $data);
                        })
                        ->rawColumns(['status'])
                        ->toJson();

                        // ->addColumn('created_by', function ($row) {
                        //     $createdBy = User::where('id', $row->request_by)->first() ?? '-';
                        //     return $createdBy?->name ?? '-';
                        // })  
                        // ->addColumn('approved_by', function ($row) {
                        //     $approvedBy = User::where('id', $row->approve_by)->first();
                        //     return $approvedBy?->name ?? '-';
                        // })
    }
}
