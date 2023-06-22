<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Classroom;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GradeClassImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{

    use SkipsFailures;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {   
        try {
            DB::beginTransaction();
            foreach ($collection as $key => $item) {
                $grade                           = new Grade;
                $grade->school_id                = session('import_school_id');
                $grade->grade_name               = $item['tingkat_kelas'];
                $grade->save();

                $classess = explode(',', $item['kelas']);

                foreach ($classess as $value){
                    $value = trim($value);
                    $cek = Classroom::firstWhere([
                        'school_id' => session('import_school_id'),
                        'grade_id' => $grade->getKey(),
                        'academic_year_id' => session('import_academic_year_id'),
                        'name' => $value
                    ]);
                    if (!$value or $value == '' or is_null($value) or $cek) continue;
                    
                    $classroom                       = new Classroom;
                    $classroom->school_id           = session('import_school_id');
                    $classroom->academic_year_id    = session('import_academic_year_id');
                    $classroom->grade_id            = $grade->getKey();
                    $classroom->name                = $value;
                    $classroom->save();
                }
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
            'tingkat_kelas' => 'required',
            'kelas' => 'required',
        ];
    }
}
