<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\TuitionType;

class StaffDatatables extends Controller
{
    //

    public function index()
    {
        $academyYear = Staff::with('school')->orderBy('created_at')->get();
        return DataTables::of($academyYear)
            ->addColumn('action', function ($row) {
                $data = [
                    'edit_url'     => route('staff.edit', ['staff' => $row->id]),
                    'delete_url'   => route('staff.destroy', ['staff' => $row->id]),
                    'redirect_url' => route('staff.index'),
                    'resource'     => 'staff',
                ];
                return view('components.datatable-action', $data);
            })->toJson();
    }
}
