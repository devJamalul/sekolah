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
        $expense = Expense::with('requested_by', 'approved_by', 'reject_by')->latest('created_at');
        return DataTables::of($expense)
            ->editColumn('expense_number', function ($expense) {
                if ($expense->status != Expense::STATUS_PENDING and $expense->status != Expense::STATUS_DRAFT) {
                    return "<a href='" . route('expense.show-detail', $expense->id) . "'>{$expense->expense_number}</a>";
                }

                return "<a href='" . route('expense.edit', $expense->id) . "'>{$expense->expense_number}</a>";
            })
            ->editColumn('approval_by', function ($row){
                if($row->status == Expense::STATUS_APPROVED || $row->status == Expense::STATUS_DONE){
                    return $row->approved_by->name;
                }
                elseif($row->status == Expense::STATUS_REJECTED){
                    return $row->reject_by->name;
                }
                else{
                    return '-';
                }
            })
            ->editColumn('status', function ($expense) {
                return match ($expense->status) {
                    Expense::STATUS_APPROVED => '<span class="badge badge-success">Disetujui</span>',
                    Expense::STATUS_PENDING => '<span class="badge badge-dark">Pending</span>',
                    Expense::STATUS_REJECTED => '<span class="badge badge-danger">Ditolak</span>',
                    Expense::STATUS_DONE => '<span class="badge badge-success">Selesai</span>',
                    Expense::STATUS_OUTGOING => '<span class="badge badge-info">Realisasi</span>',
                    Expense::STATUS_DRAFT => '<span class="badge badge-secondary">Draft</span>',
                };
            })
            ->addColumn('action', function ($expense) {
                $data = [
                    'edit_url'     => route('expense.edit', ['expense' => $expense->id]),
                    'delete_url'   => route('expense.destroy', ['expense' => $expense->id]),
                    'redirect_url' => route('expense.index'),
                    'resource'     => 'expense',
                    'custom_links' => [],
                ];

                if ($expense->status == Expense::STATUS_DRAFT) {
                    array_push($data['custom_links'], ['label' => 'Publish', 'url' => route('expense.publish', ['expense' => $expense->id]), 'name' => 'expense.publish']);
                }

                if ($expense->status == Expense::STATUS_PENDING) {
                    $data['edit_url'] = null;
                }
                
                if ($expense->status != Expense::STATUS_DRAFT and $expense->status != Expense::STATUS_PENDING) {
                    $data['edit_url'] = null;
                    $data['delete_url'] = null;
                    array_push($data['custom_links'], ['label' => 'Detail', 'url' => route('expense.show-detail', $expense->id), 'name' => 'expense.show-detail']);
                }

                return view('components.datatable-action', $data);
            })
            ->rawColumns(['status', 'action', 'expense_number'])
            ->filterColumn('status', function($query, $keyword) {
                switch (strtolower($keyword)){
                    case 'disetujui': case 'setuju':
                        $match = Expense::STATUS_APPROVED;
                        break;
                    case 'pending': case 'pen': case 'pend':
                        $match = Expense::STATUS_PENDING;
                        break;
                    case 'ditolak': case 'tolak':
                        $match = Expense::STATUS_REJECTED;
                        break;
                    case 'selesai': case 'sel':
                        $match = Expense::STATUS_DONE;
                        break;
                    case 'realisasi': case 'real':
                        $match = Expense::STATUS_OUTGOING;
                        break;
                    case 'draft': case 'draf':
                        $match = Expense::STATUS_DRAFT;
                        break;
                    default:
                        $match = null;
                }
                $query->where('status', $match);
            })
            ->toJson();
    }
}
