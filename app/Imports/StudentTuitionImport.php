<?php

namespace App\Imports;

use Exception;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Tuition;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\StudentTuitionMaster;
use App\Models\TuitionType;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentTuitionImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{

    use SkipsFailures;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // dd($collection);
        try {

            DB::beginTransaction();
            foreach ($collection as $key => $item) {
                // Save Student
                $student                            = new Student;
                $student->school_id                 = session('import_school_id');

                $student->name                      = $item['nama'];
                $student->gender                    = $item['jenis_kelamin'];
                $student->address                   = $item['alamat'];
                $student->email                     = $item['email'];
                $student->dob                       = date('Y-m-d H:i:s', strtotime($item['tanggal_lahir']));
                $student->religion                  = $item['agama'];
                $student->phone_number              = $item['no_telp'];
                $student->family_card_number        = $item['no_kk'];
                $student->nik                       = $item['nik'];
                $student->nis                       = $item['nis'];
                $student->nisn                      = $item['nisn'];

                $student->father_name               = $item['nama_ayah'];
                $student->father_address            = $item['alamat_ayah'];
                $student->father_email              = $item['email_ayah'];
                $student->father_phone_number       = $item['no_telepon_ayah'];

                $student->mother_name               = $item['nama_ibu'];
                $student->mother_address            = $item['alamat_ibu'];
                $student->mother_email              = $item['email_ibu'];
                $student->mother_phone_number       = $item['no_telp_ibu'];

                $student->guardian_name             = $item['nama_wali'];
                $student->guardian_address          = $item['alamat_wali'];
                $student->guardian_email            = $item['email_wali'];
                $student->guardian_phone_number     = $item['nomor_telepon_wali'];

                $student->save();
                // End Save Student

                // assign student to classroom
                info('kelas ' . $item['kelas']);
                info('school_id ' . session('import_school_id'));
                info('academic_year_id ' . session('import_academic_year_id'));
                $class = Classroom::withoutGlobalScopes()->where([
                    'name' => $item['kelas'],
                    'school_id' => session('import_school_id'),
                    'academic_year_id' => session('import_academic_year_id')
                ])->first();
                info('grade_id ' . $class->grade_id);

                $classroomStudent = ClassroomStudent::firstOrNew([
                    'classroom_id' => $class->getKey(),
                    'student_id' => $student->getKey()
                ]);
                $classroomStudent->save();


                // Student Tuition Master
                $tuition_type = TuitionType::withoutGlobalScopes()->firstWhere([
                    'school_id' => session('import_school_id'),
                    'name' => $item['tipe_uang_sekolah']
                ]);

                $tuition = Tuition::withoutGlobalScopes()->where([
                    'school_id' => session('import_school_id'),
                    'tuition_type_id' => $tuition_type->getKey(),
                    'academic_year_id' => session('import_academic_year_id'),
                    'grade_id' => $class->grade_id
                ])->first();

                info($tuition);

                $studentTuitionMaster = StudentTuitionMaster::firstOrNew([
                    'student_id' => $student->getKey(),
                    'tuition_id' => $tuition->getKey()
                ]);
                $studentTuitionMaster->save();
            }
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            DB::rollBack();
            session()->forget(['import_school_id', 'import_academic_year_id', 'import_grade_id']);
            $failures = $e->failures();
            info($e->failures());

            if (count($failures) > 0) {
                $row = $failures[0]->row(); // row that went wrong
                $column = $failures[0]->attribute(); // either heading key (if using heading row concern) or column index
                $error = $failures[0]->errors(); // Actual error messages from Laravel validator
                // $value = $failures[0]->values(); // The values of the row that has failed.

            }
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|max:1|in:L,P',
            'alamat' => 'required',
            'agama' => 'required',
            'no_telp' => 'nullable|max:20',
            'nik' => 'required|numeric|max_digits:16',
            'no_kk' => 'required|max_digits:16',
            'nis' => 'nullable|numeric|max_digits:20',
            'nisn' => 'nullable|numeric|max_digits:10',

            'nama_ayah' => 'required',
            'alamat_ayah' => 'nullable',
            'email_ayah' => 'nullable|email',
            'no_telepon_ayah' => 'nullable|max:20',

            'nama_ibu' => 'required',
            'alamat_ibu' => 'nullable',
            'email_ibu' => 'nullable|email',
            'no_telp_ibu' => 'nullable|max:20',

            'nama_wali' => 'nullable',
            'alamat_wali' => 'nullable',
            'email_wali' => 'nullable|email',
            'nomor_telepon_wali' => 'nullable|max:20',

            'kelas' => 'required',
            'nominal' => 'nullable|numeric',
            'tipe_uang_sekolah' => 'nullable',
        ];
    }
}