<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $academyYear = Student::with('classrooms')
            ->where('name', 'like', '%' . session('transaction_keyword') . '%')
            ->orWhere('nis', 'like', '%' . session('transaction_keyword') . '%')
            ->orWhere('nisn', 'like', '%' . session('transaction_keyword') . '%');
        return DataTables::of($academyYear)
            ->addColumn('action', function (Student $row) {
                return view('components.datatable-action');
            })
            ->addColumn('nis', fn ($row) => "<a href='".route('transactions.show', $row->getKey())."'>" . $row->nis . "</a>")
            ->addColumn('nama', fn ($row) => "<a href='" . route('transactions.show', $row->getKey()) . "'>" . $row->name . "</a>")
            ->addColumn('ortu', fn ($row) => $row->father_name . "<br />" . $row->mother_name . "<br />" . $row->guardian_name)
            ->addColumn('kelas', fn ($row) => $row?->classrooms()->latest()->first()?->grade->grade_name . " " . $row?->classrooms()->latest()->first()?->name)
            ->rawColumns(['nis', 'nama', 'ortu', 'kelas', 'action'])
            ->startsWithSearch(false)
            ->toJson();
    }
}
