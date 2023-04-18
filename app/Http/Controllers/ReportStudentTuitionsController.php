<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\StudentTuition;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportStudentTuitionsExport;

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

        $exportName = Str::slug('laporan-pembayaran-sekolah');
        $exportName .= '-';
        $exportName .= \Carbon\Carbon::parse(now())->format('m-d-Y-hs');



        $exportStudentTuitions = new ReportStudentTuitionsExport($request);

        return match ($request->export) {
            'excel' => $this->exportByExcel($exportStudentTuitions, $exportName),
            'pdf' => $this->exportByPdf($exportStudentTuitions, $exportName),
        };
    }


    public function exportByPdf($studentTuition, $exportName)
    {
        return Excel::download($studentTuition, $exportName . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportByExcel($studentTuition, $exportName)
    {
        return Excel::download($studentTuition, $exportName . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
