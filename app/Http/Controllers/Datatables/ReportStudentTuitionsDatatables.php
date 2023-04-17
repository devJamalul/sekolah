<?php

namespace App\Http\Controllers\Datatables;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StudentTuition;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\StudentTuitionPaymentHistory;

class ReportStudentTuitionsDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {




        $studentTuitions = StudentTuition::with(
            [
                'student_tuition_details.tuition.tuition_type',
                'student_tuition_details.tuition.academic_year',
                'student_tuition_details.tuition.grade',
                'student_tuition_payment_histories',
                'student.classrooms',
                'payment_type'
            ]
        )->has('student_tuition_details.tuition')
            ->when($request->has('reportrange'), function ($q) use ($request) {
                $reportDate = $this->parseDate($request->reportrange);
                $q->whereBetween('created_at', [
                    $reportDate->transaction_report_start->startOfDay()->format('Y-m-d H:i:s'),
                    $reportDate->transaction_report_end->endOfDay()->format('Y-m-d H:i:s'),
                ]);
            })
            ->when($request->has('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->has('academyYear'), function ($q) use ($request) {
                $academyYear = $request->academyYear;
                $q->whereHas('student_tuition_details.tuition.academic_year', function ($query) use ($academyYear) {
                    $query->where('id', $academyYear);
                });
            })
            ->when($request->has('student'), function ($q) use ($request) {
                $q->where('student_id', $request->student);
            })
            ->when($request->has('bill'), function ($q) use ($request) {
                $q->where('bill_number', $request->bill);
            });

        $total_payment = $studentTuitions->sum('grand_total');
        $totalRemainingDebt = StudentTuitionPaymentHistory::whereIn('student_tuition_id', $studentTuitions->pluck('id'))->sum('price');
        $totalRemainingDebt = $total_payment - $totalRemainingDebt;


        return DataTables::of($studentTuitions)
            ->addColumn('bill_num', fn (StudentTuition $row) => $row->bill_number)
            ->addColumn('name_student', fn (StudentTuition $row) => $row->student->name)
            ->addColumn('payment_type', fn (StudentTuition $row) => $row->payment_type?->name)
            ->addColumn('date_invoice', fn (StudentTuition $row) => $row->created_at->format('d F Y'))
            ->addColumn('academy_year', function (StudentTuition $row) {
                $tuition = $row->student_tuition_details->first();
                return $tuition->tuition->academic_year->academic_year_name;
            })
            ->addColumn('classrooms', function (StudentTuition $row) {
                $student = $row->student->classrooms->first();
                return $student->name;
            })
            ->addColumn('tuition_type', function (StudentTuition $row) {
                $tuitionType = $row->student_tuition_details->map(function ($row) {
                    return '<span class="badge badge-success">' . $row->tuition->tuition_type->name . '</span>';
                })->implode(' ');
                return $tuitionType;
            })
            ->addColumn('grade', function (StudentTuition $row) {
                $tuition = $row->student_tuition_details->first();
                return $tuition->tuition->grade->grade_name;
            })
            ->addColumn('remaining_debt', function (StudentTuition $row) {
                $remainingDebt = $row->grand_total - $row->student_tuition_payment_histories->sum('price');
                return $remainingDebt > 0 ? $remainingDebt : 0;
            })
            ->addColumn('grand_total', function (StudentTuition $row) {
                $price = $row->student_tuition_details->map(function ($row) {
                    return $row->price;
                })->implode('&#013;');
                return  '<span data-toggle="popover" data-placement="bottom" title="' . $price . '">' . $row->grand_total . '</span>';
            })
            ->addColumn('status_payment', function (StudentTuition $row) {
                return match ($row->status) {
                    StudentTuition::STATUS_PAID => '<span class="badge badge-success">Lunas</span',
                    StudentTuition::STATUS_PENDING => '<span class="badge badge-danger">Belum Lunas</span',
                    StudentTuition::STATUS_PARTIAL => '<span class="badge badge-warning">Belum Lunas</span',
                };
            })
            ->with([
                'total_payment' => $total_payment,
                'total_remaining_debt' => $totalRemainingDebt > 0 ? $totalRemainingDebt : 0
            ])
            ->rawColumns(['status_payment', 'grand_total', 'tuition_type'])

            ->only([
                'bill_num',
                'name_student',
                'academy_year',
                'grade',
                'classrooms',
                'tuition_type',
                'payment_type',
                'remaining_debt',
                'grand_total',
                'status_payment',
                'date_invoice'
            ])

            ->toJson();
    }


    public function parseDate($tanggal): object
    {
        $tgl = explode(" - ", $tanggal);

        return (object) [
            'transaction_report_start' => Carbon::parse($tgl[0]),
            'transaction_report_end' => Carbon::parse($tgl[1])
        ];
    }
}
