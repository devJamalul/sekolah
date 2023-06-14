<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Tuition;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class TuitionDatatables extends Controller
{
    //

    public function index()
    {
        $tuition = Tuition::with('tuition_type', 'academic_year', 'grade', 'requested_by', 'approved_by');
        return DataTables::of($tuition)
            ->filterColumn('academic_year', function ($query, $keyword) {
                $query->whereHas('academic_year', function ($q) use ($keyword) {
                    $q->where('academic_year_name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('grade', function ($query, $keyword) {
                $query->whereHas('grade', function ($q) use ($keyword) {
                    $q->where('grade_name', 'like', "%$keyword%");
                });
            })
            ->editColumn('tuition_type', function ($row) {
                return $row->tuition_type ? $row->tuition_type->name : '-';
            })
            ->editColumn('academic_year', function ($row) {
                return $row->academic_year ? $row->academic_year->academic_year_name : '-';
            })
            ->editColumn('grade', function ($row) {
                return $row->grade ? $row->grade->grade_name : '-';
            })
            ->editColumn('price', function ($row) {
                return 'Rp. ' . number_format($row->price, 0, ',', '.');
            })
            ->editColumn('request_by', function ($row) {
                return $row->requested_by->name;
            })
            ->editColumn('status', function ($row) {
                return match ($row->status) {
                    Tuition::STATUS_PENDING => '<span class="badge badge-dark">Menunggu</span>',
                    Tuition::STATUS_APPROVED => '<span class="badge badge-success">Diterima</span>',
                    Tuition::STATUS_REJECTED => '<span class="badge badge-danger">Ditolak</span>',
                };
                // return $row->requested_by->name;
            })
            ->editColumn('approval_by', function ($row) {
                return match ($row->status) {
                    Tuition::STATUS_PENDING => '-',
                    Tuition::STATUS_APPROVED => '<span class="badge badge-success">' . $row->approved_by->name . '</span>',
                    Tuition::STATUS_REJECTED => '<span class="badge badge-danger">' . $row->rejector->name . '</span>',
                };
                // return $row->approved_by ? $row->approved_by->name : '-';
            })
            ->addColumn('action', function (Tuition $row) {
                $data = [
                    'edit_url'     => $row->status == Tuition::STATUS_PENDING ? route('tuition.edit', ['tuition' => $row->id]) : null,
                    'delete_url'   => $row->status == Tuition::STATUS_PENDING ? route('tuition.destroy', ['tuition' => $row->id]) : null,
                    'redirect_url' => route('tuition.index'),
                    'resource' => 'tuition'
                ];
                return view('components.datatable-action', $data);
            })
            ->rawColumns(['status', 'approval_by'])
            ->toJson();
    }
}
