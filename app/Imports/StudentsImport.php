<?php

namespace App\Imports;

use App\Models\Student;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $school_id;

    public function __construct(string $school_id)
    {
        $this->school_id = $school_id;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try {
            DB::beginTransaction();
            foreach ($collection as $key => $item) {
                // Save Student
                    $student                            = new Student;
                    $student->school_id                 = $this->school_id;
    
                    $student->name                      = $item['nama'];
                    $student->gender                    = $item['jenis_kelamin'];
                    $student->address                   = $item['alamat'];
                    $student->dob                       = date('Y-m-d H:i:s', strtotime($item['tanggal_lahir']));
                    $student->religion                  = $item['agama'];
                    $student->phone_number              = $item['nomor_telepon'];
                    $student->family_card_number        = $item['nomor_kartu_keluarga'];
                    $student->nik                       = $item['nik'];
                    $student->nis                       = $item['nis'];
                    $student->nisn                      = $item['nisn'];
    
                    $student->father_name               = $item['nama_ayah'];
                    $student->father_address            = $item['alamat_ayah'];
                    $student->father_phone_number       = $item['nomor_telepon_ayah'];
    
                    $student->mother_name               = $item['nama_ibu'];
                    $student->mother_address            = $item['alamat_ibu'];
                    $student->mother_phone_number       = $item['nomor_telepon_ibu'];
    
                    $student->guardian_name             = $item['nama_wali'];
                    $student->guardian_address          = $item['alamat_wali'];
                    $student->guardian_phone_number     = $item['nomor_telepon_wali'];
    
                    $student->save();
                // End Save Student
            }
            DB::commit();
            return redirect()->route('students.index')->withToastSuccess('Berhasil mengimpor data siswa!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();

            if (count($failures) > 0) {
                $row = $failures[0]->row(); // row that went wrong
                $column = $failures[0]->attribute(); // either heading key (if using heading row concern) or column index
                $error = $failures[0]->errors(); // Actual error messages from Laravel validator
                // $value = $failures[0]->values(); // The values of the row that has failed.
                
                return redirect()->route('students.index')->withToastError("Terjadi kesalahan pada Baris $row, Kolom $column, dengan pesan $error[0]");
            }
        } catch (ValidationException $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError($ex->errors());
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError("Ops, ada kesalahan saat mengimpor data siswa!");
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|max:1',
            'alamat' => 'required',
            'agama' => 'required',
            'no_telepon' => 'nullable|max:20',
            'nik' => 'required|numeric|max_digits:16',
            'nis' => 'nullable|numeric|max_digits:20',
            'nisn' => 'nullable|numeric|max_digits:10',

            'nama_ayah' => 'required',
            'alamat_ayah' => 'nullable',
            'nomor_telepon_ayah' => 'nullable|max:20',

            'nama_ibu' => 'required',
            'alamat_ibu' => 'nullable',
            'nomor_telepon_ibu' => 'nullable|max:20',

            'nama_wali' => 'nullable',
            'alamat_wali' => 'nullable',
            'nomor_telepon_wali' => 'nullable|max:20',
        ];
    }
}
