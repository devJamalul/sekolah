<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class StudentsExport implements FromView, WithColumnWidths
{
    function __construct(protected $request)
    {
    }

    public function view(): View
    {
        $students = $this->request;
        return view('exports.report-students', compact('students'));
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 40,            
            'C' => 20,            
            'D' => 40,            
            'E' => 20,            
        ];
    }
}
