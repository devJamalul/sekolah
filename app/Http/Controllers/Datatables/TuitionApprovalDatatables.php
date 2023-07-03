<?php

namespace App\Http\Controllers\Datatables;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentTuitionMaster;
use App\Models\Tuition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuitionApprovalDatatables extends Controller
{
    public function index(Request $request)
    {
        $tuitions = Tuition::where('school_id', session('school_id'))
            ->where('status', Tuition::STATUS_PENDING)
            ->with('tuition_type', 'grade')
            // ->withTrashed()
            ->get();

        return DataTables::of($tuitions)
            ->addColumn('tuition_name', function ($row) {
                return $row->tuition_type->name;
            })
            ->addColumn('price', function ($row) {
                return 'Rp. ' . number_format($row->price, 0, ',', '.');
            })
            ->editColumn('grade', function ($row) {
                return $row->grade->grade_name;
            })
            ->addColumn('status', function ($row) {
                switch ($row->status) {
                    case 'rejected':
                        return '<span class="badge badge-danger">Ditolak</span>';
                        break;
                    case 'approved':
                        return '<span class="badge badge-success">Disetujui</span>';
                        break;
                    default:
                        return '<span class="badge badge-primary">Menunggu Persetujuan</span>';
                        break;
                }
            })
            ->addColumn('action', function (Tuition $row) use ($request) {
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
    }
}
