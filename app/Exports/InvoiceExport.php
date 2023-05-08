<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class InvoiceExport implements FromView, WithColumnWidths
{
    function __construct(protected $request, protected $filename)
    {
    }

    public function view(): View
    {
        $invoices = $this->request;
        $filename = $this->filename;
        return view('exports.report-invoice', compact('invoices', 'filename'));
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 40,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 30,
        ];
    }
}
