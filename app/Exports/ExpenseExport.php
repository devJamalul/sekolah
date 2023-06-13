<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExpenseExport implements FromView, WithColumnWidths
{
    function __construct(protected $request)
    {
    }

    public function view(): View
    {
        $expense = $this->request;
        return view('exports.report-expense', compact('expense'));
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,            
            'C' => 20,             
            'D' => 20,           
            'E' => 20,          
            'F' => 20,          
            'G' => 20,           
        ];
    }
}
