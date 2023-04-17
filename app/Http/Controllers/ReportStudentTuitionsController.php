<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\StudentTuition;

class ReportStudentTuitionsController extends Controller
{

    protected $title = "Laporan Pembayaran Sekolah";


    public function index()
    {
        $title = $this->title;
        $student = Student::all();
        $statusPayment = [
            StudentTuition::STATUS_PAID,
            StudentTuition::STATUS_PARTIAL,
            StudentTuition::STATUS_PENDING,
        ];
        $academicYear = AcademicYear::where("status_years", '!=', AcademicYear::STATUS_REGISTRATION)->orderBy('academic_year_name', 'desc')->get();

        return view('pages.report.student-tuitions.index', compact('student', 'title', 'academicYear', 'statusPayment'));
    }


    public function export(Request $request)
    {
        if ($request->has('export') == false && in_array($request->export, ['pdf', 'excel'])) {
            return redirect()->route('report-student-tuition', $tuitionType->id)->withToastError("Ops Gagal Export {$this->title}!");
        }

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
            })->get();

        return match ($request->export) {
            'excel' => $this->exportByExcel($studentTuitions),
            'pdf' => $this->exportByPdf($studentTuitions),
        };
    }


    public function exportByPdf($studentTuition)
    {
        dd($studentTuition);
    }

    public function exportByExcel($studentTuition)
    {
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
