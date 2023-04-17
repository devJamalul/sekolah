<?php

namespace App\Exports;

use App\Models\StudentTuition;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportStudentTuitionsExport implements FromView
{
    function __construct(protected  $request)
    {
    }


    public function view(): View
    {
        $request = $this->request;

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
            ->when($request->has('reportrange') && $request->reportrange != null, function ($q) use ($request) {
                $reportDate = $this->parseDate($request->reportrange);
                $q->whereBetween('created_at', [
                    $reportDate->transaction_report_start->startOfDay()->format('Y-m-d H:i:s'),
                    $reportDate->transaction_report_end->endOfDay()->format('Y-m-d H:i:s'),
                ]);
            })
            ->when($request->has('status') && $request->status != null, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->has('academyYear') && $request->academyYear != null, function ($q) use ($request) {
                $academyYear = $request->academyYear;
                $q->whereHas('student_tuition_details.tuition.academic_year', function ($query) use ($academyYear) {
                    $query->where('id', $academyYear);
                });
            })
            ->when($request->has('student')  && $request->student != null, function ($q) use ($request) {
                $q->where('student_id', $request->student);
            })
            ->when($request->has('bill') && $request->bill != null, function ($q) use ($request) {
                $q->where('bill_number', $request->bill);
            })->get();

        $statusPayment =  fn ($status) => match ($status) {
            StudentTuition::STATUS_PAID => 'Lunas',
            StudentTuition::STATUS_PENDING => 'Belum Lunas',
            StudentTuition::STATUS_PARTIAL => 'Belum Lunas',
        };

        return view('exports.report-student-tuitions', compact('studentTuitions', 'statusPayment'));
    }
}
