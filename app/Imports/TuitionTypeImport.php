<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Grade;
use App\Models\Staff;
use App\Models\Classroom;
use App\Models\Tuition;
use App\Models\TuitionType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TuitionTypeImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
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

                $tuitionType = TuitionType::withoutGlobalScopes()->firstOrNew([
                    'school_id' => session('import_school_id'),
                    'name' => $item['nama'],
                    'recurring' => $item['rutin'] == 'y' ? 1 : 0,
                ]);
                $tuitionType->save();

                $tuition = Tuition::firstOrNew([
                    'school_id' => session('import_school_id'),
                    'tuition_type_id' => $tuitionType->getKey(),
                    'academic_year_id' => session('import_academic_year_id'),
                    'price' => $item['nominal'],
                    'grade_id' => session('grade_' . str($item['tingkat_kelas'])->slug())
                ]);
                $tuition->save();
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
            'rutin' => 'required|max:1|in:y,n',
            'tingkat_kelas' => 'required',
            'nominal' => 'required|numeric',
        ];
    }
}