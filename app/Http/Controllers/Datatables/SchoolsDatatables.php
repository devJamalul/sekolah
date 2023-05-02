<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SchoolsDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $academyYear = School::with('parent', 'staf.user');
        return DataTables::of($academyYear)
            ->addColumn('action', function (School $row) {
                $data = [
                    'edit_url'     => route('schools.edit', ['school' => $row->id]),
                    'delete_url'   => route('schools.destroy', ['school' => $row->id]),
                    'redirect_url' => route('schools.index')
                ];
                return view('components.datatable-action', $data);
            })
            ->addColumn('pic_name', fn ($row) => $row->staf?->user?->name ?? '-')
            ->addColumn('pic_email', fn ($row) => $row->staf?->user?->email ?? '-')
            ->editColumn('induk', function ($row) {
                return $row?->parent?->school_name ?? '-';
            })
            ->filterColumn('pic_name', function ($query, $keyword) {
                $query->whereHas('staf.user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->filterColumn('pic_email', function ($query, $keyword) {
                $query->whereHas('staf.user', function ($q) use ($keyword) {
                    $q->where('email', 'like', '%' . $keyword . '%');
                });
            })
            ->startsWithSearch(false)
            ->toJson();
    }
}
