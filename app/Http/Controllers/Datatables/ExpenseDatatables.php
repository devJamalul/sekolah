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
            ->editColumn('request_by', function ($row){
                return $row->requested_by->name;
            })
            ->editColumn('approval_by', function ($row){
                return $row->approved_by->name;
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
                            'url' => route('expense.show', ['expense' => $row->id])
                        ]
                    ]
                ];
                return view('components.datatable-action', $data);
            })->toJson();
    }
}
