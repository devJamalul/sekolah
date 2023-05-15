<?php

namespace App\Http\Controllers\Datatables;

use App\Models\Staff;
use App\Models\TuitionType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class StaffDatatables extends Controller
{
    //

    public function index()
    {
        $academyYear = Staff::with('school')->orderBy('created_at')->get();
        return DataTables::of($academyYear)
            ->editColumn('name', function ($data) {
                return "<a href='" . route('staff.show', $data->getKey()) . "'>" . Str::of($data->name)->limit(20, '...') . "</a>";
            })
            ->editColumn('gender', function ($data) {
                return $data->gender;
            })
            ->editColumn('address', function ($data) {
                return Str::of($data->address)->limit(40, '...');
            })
            ->addColumn('action', function (Staff $row) {
                $data = [
                    'edit_url'     => route('staff.edit', ['staff' => $row->id]),
                    'delete_url'   => route('staff.destroy', ['staff' => $row->id]),
                    'redirect_url' => route('staff.index'),
                    'resource'     => 'staff',
                ];
                return view('components.datatable-action', $data);
            })->rawColumns(['name'])
            ->toJson();
    }
}
