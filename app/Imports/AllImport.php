<?php

namespace App\Imports;

use App\Imports\StaffImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllImport implements WithMultipleSheets
{
  public function sheets(): array
  {
    return [
        'General' => new GeneralImport(),
        'Tingkatan' => new GradeClassImport(),
        'Data Guru & staff' => new StaffImport(),
        'Data Dompet' => new WalletImport(),
        'Tipe Uang Sekolah' => new TuitionTypeImport(),
        'Data Siswa' => new StudentTuitionImport(),
    ];
  }

  // public function onUnknownSheet($sheetName)
  // {
  //       info("Sheet {$sheetName} was not found");
  // }
}
