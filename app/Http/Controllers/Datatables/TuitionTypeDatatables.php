<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\TuitionType;

class TuitionTypeDatatables extends Controller
{
    //

    public function index()
    {
        $academyYear = TuitionType::with('school')->orderBy('created_at')->get();
        return DataTables::of($academyYear)
            ->editColumn('recurring', fn ($item) => $item->recurring == 1 ? 'Ya' : 'Tidak')
            ->addColumn('action', function ($row) {
                $data = [
                    'edit_url'     => route('tuition-type.edit', ['tuition_type' => $row->id]),
                    'delete_url'   => route('tuition-type.destroy', ['tuition_type' => $row->id]),
                    'redirect_url' => route('tuition-type.index'),
                    'resource'     => 'tuition-type',
                ];
                return view('components.datatable-action', $data);
            })->toJson();
    }
}
