<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Grade;
use App\Models\Staff;
use App\Models\Classroom;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StaffImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{

    use SkipsFailures;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        DB::beginTransaction();
        try {
            foreach ($collection as $key => $item) {
                $staff                           = new Staff;
                $staff->school_id                = session('import_school_id');
                $staff->name                     = $item['nama'];
                $staff->gender                   = $item['jenis_kelamin'];
                $staff->address                  = $item['alamat'];
                $staff->dob                      = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item['tanggal_lahir']));
                $staff->religion                 = $item['agama'];
                $staff->phone_number             = $item['no_telp'];
                $staff->family_card_number       = $item['no_kk'];
                $staff->nik                      = $item['nik'];
                $staff->save();
            }
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
           DB::rollBack();
            session()->forget(['import_school_id', 'import_academic_year_id', 'import_grade_id']);
            $failures = $e->failures();

            if (count($failures) > 0) {
                $row = $failures[0]->row(); // row that went wrong
                $column = $failures[0]->attribute(); // either heading key (if using heading row concern) or column index
                $error = $failures[0]->errors(); // Actual error messages from Laravel validator
                // $value = $failures[0]->values(); // The values of the row that has failed.
                
                return redirect()->back()->withToastError("Terjadi kesalahan pada Baris $row, Kolom $column, dengan pesan $error[0]");
            }
        }
    }

    public function rules(): array
    {
        return [
            'nik' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|numeric|max_digits:16    ',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|max:1|in:L,P',
            'agama' => 'required',
            'no_kk' => 'nullable',
        ];
    }
}
