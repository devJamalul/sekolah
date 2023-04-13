<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\StudentTuitionPaymentHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionReportDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = StudentTuitionPaymentHistory::with('student_tuition.student', 'payment_type')
        ->whereBetween('student_tuition_payment_histories.created_at', [
            session('transaction_report_start')->startOfDay()->format('Y-m-d H:i:s'),
            session('transaction_report_end')->endOfDay()->format('Y-m-d H:i:s'),
        ]);
        return DataTables::of($data)
            ->addColumn('name', fn (StudentTuitionPaymentHistory $row) => $row->student_tuition->student->name)
            ->addColumn('class', fn (StudentTuitionPaymentHistory $row) => $row->student_tuition->student->classrooms()->latest()->first()->grade->grade_name . " " . $row->student_tuition->student->classrooms()->latest()->first()->name)
            ->addColumn('student_tuition', fn (StudentTuitionPaymentHistory $row) => $row->student_tuition->note . " " . $row->student_tuition->period->format('F Y'))
            ->addColumn('nominal', fn (StudentTuitionPaymentHistory $row) => number_format($row->price, 0, ',', '.'))
            ->addColumn('payment_type', fn (StudentTuitionPaymentHistory $row) => $row->payment_type->name)
            ->addColumn('tanggal', fn (StudentTuitionPaymentHistory $row) => $row->created_at->format('d F Y, H:i'))
            ->rawColumns(['name', 'class', 'student_tuition', 'nominal', 'payment_type', 'tanggal'])
            ->toJson();
    }
}
