<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Yajra\DataTables\DataTables;

class ExpenseDatatables extends Controller
{
    //
    public function index()
    {
        $expense = Expense::with('requested_by', 'approved_by')->latest('created_at');
        return DataTables::of($expense)
            ->editColumn('expense_number', function ($row){
                return "<a href='" . route('expense.show', $row->id) . "'>{$row->expense_number}</a>";
            
            })
            ->editColumn('request_by', function ($row){
                return $row->requested_by->name;
            })
            ->editColumn('status', function ($row){
                return match ($row->status) {
                    Expense::STATUS_APPROVED => '<span class="badge badge-success">Disetujui</span>',
                    Expense::STATUS_PENDING => '<span class="badge badge-warning">Pending</span>',
                    Expense::STATUS_REJECTED => '<span class="badge badge-danger">Ditolak</span>'
                };
            })
            ->editColumn('is_sempoa_processed', function ($row){
                return $row->is_sempoa_processed == 0 ? 'Belum' : 'Sudah';
            })
            ->addColumn('action', function (Expense $row) {
                $data = [
                    'edit_url'     => route('expense.edit', ['expense' => $row->id]),
                    'delete_url'   => route('expense.destroy', ['expense' => $row->id]),
                    'redirect_url' => route('expense.index'),
                    'resource'     => 'expense',
                    'custom_links' => [
                        [
                            'label' => 'Detail',
                            'url' => route('expense.show', ['expense' => $row->id]),
                            'name' => 'expense.show'
                        ],
                    ]
                ];
                return view('components.datatable-action', $data);

            })
            ->rawColumns(['status', 'action', 'expense_number'])
            ->toJson();
    }
}
