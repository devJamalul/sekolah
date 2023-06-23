<?php

namespace App\Imports;

use App\Models\Wallet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WalletImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
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
                $wallet                           = new Wallet;
                $wallet->school_id                = session('import_school_id');
                $wallet->name                     = $item['nama'];
                $wallet->init_value               = $item['saldo_awal'];
                $wallet->last_balance             = 0;
                $wallet->danabos                  = $item['dana_bos'] == 'y' ? 1 : 0;
                $wallet->save(); 
            }
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            session()->forget(['import_school_id', 'import_academic_year_id']);
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
            'nama' => 'required',
            'dana_bos' => 'required|max:1|in:y,n',
            'saldo_awal' => 'required|numeric',
        ];
    }
}
