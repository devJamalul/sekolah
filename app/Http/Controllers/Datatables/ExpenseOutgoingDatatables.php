<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Expense;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ExpenseDetail;

class ExpenseOutgoingDatatables extends Controller
{
    public function index(Request $request)
    {
        $expense = Expense::where('school_id', session('school_id'))
            ->where('status', Expense::STATUS_APPROVED)
            ->orderByDesc('created_at')
            ->get();

        return DataTables::of($expense)
            ->addColumn('expense_number', function ($row) {
                return $row->expense_number;
            })
            ->addColumn('expense_date', function ($row) {
                return $row->expense_date;
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    Expense::STATUS_APPROVED => '<span class="badge badge-success">Disetujui</span>',
                    Expense::STATUS_PENDING => '<span class="badge badge-dark">Pending</span>',
                    Expense::STATUS_REJECTED => '<span class="badge badge-danger">Ditolak</span>',
                    Expense::STATUS_DONE => '<span class="badge badge-success">Selesai</span>',
                    Expense::STATUS_OUTGOING => '<span class="badge badge-info">Realisasi</span>',
                    Expense::STATUS_DRAFT => '<span class="badge badge-secondary">Draft</span>',
                };
            })

            ->addColumn('total', function ($row) {
                return 'Rp. ' . number_format($row->price, 0, ', ', '.');
            })
            ->addColumn('action', function (Expense $row) use ($request) {
                $data = [
                    'redirect_url' => route('expense-outgoing.index'),
                    'resource'     => 'expense-outgoing',
                    'custom_links' => [
                        [
                            'label' => 'Detail',
                            'url' => route('expense-outgoing.show', ['expense_outgoing' => $row->getKey()]),
                        ]
                    ]
                ];
                return view('components.datatable-action', $data);
            })
            ->rawColumns(['status'])
            ->toJson();
    }
}
