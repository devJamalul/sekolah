<?php

namespace App\Imports;

use App\Imports\StaffImport;
use App\Imports\GeneralImport;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllImport implements WithMultipleSheets, WithChunkReading, ShouldQueue
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

  public function chunkSize(): int
  {
      return 1000;
  }
}
