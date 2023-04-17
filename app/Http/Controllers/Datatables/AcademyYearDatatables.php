<?php

namespace App\Http\Controllers\Datatables;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class AcademyYearDatatables extends Controller
{
    //
    public function index()
    {
        $academyYear = AcademicYear::with('school')->orderBy('status_years');

        return DataTables::of($academyYear)

            ->addColumn('status_years', function ($row) {
                return match ($row->status_years) {
                    AcademicYear::STATUS_STARTED => '<span class="badge badge-success">Aktif</span>',
                    AcademicYear::STATUS_REGISTRATION => '<span class="badge badge-warning">Register</span>',
                    AcademicYear::STATUS_CLOSED => '<span class="badge badge-danger">Ditutup</span>'
                };
            })
            ->editColumn('year_start', function ($row) {

                return $row->year_start;
            })
            ->editColumn('year_end', function ($row) {
                return $row->year_end;
            })
            ->addColumn('action', function (AcademicYear $row) {
                $data = [
                    'edit_url'     => route('academy-year.edit', ['academy_year' => $row->id]),
                    'delete_url'   => route('academy-year.destroy', ['academy_year' => $row->id]),
                    'redirect_url' => route('academy-year.index'),
                    'resource'     => 'academy-year',
                ];
                return view('components.datatable-action', $data);
            })
            ->rawColumns(['status_years', 'action'])
            ->toJson();
    }
}
