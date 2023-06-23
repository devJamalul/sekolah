<?php

namespace App\Imports;

use App\Actions\User\NewUser;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\School;
use App\Models\AcademicYear;
use App\Models\Staff;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;

class GeneralImport implements ToCollection, WithStartRow, SkipsEmptyRows, SkipsOnFailure
{

    use SkipsFailures;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        DB::beginTransaction();
        try {
            $dataGeneral = array_column($collection->toArray(), 1);

            $regex = preg_match_all('/\d{4}/', $dataGeneral[10], $academicYear);

            $school = School::firstOrCreate(
                [
                    'school_name'  => $dataGeneral[0],
                    'grade'        => $dataGeneral[1],
                    'address'       => $dataGeneral[2],
                    'email'        => $dataGeneral[3],
                    'phone'        => $dataGeneral[4]
                ],
            );

            $school->foundation_head_name = $dataGeneral[5];
            $school->foundation_head_email = $dataGeneral[6];
            $school->foundation_head_tlpn = $dataGeneral[7];
            $school->save();

            session(['import_school_id' => $school->getKey()]);

            // dd(Carbon::now()->year($academicYear[0][1]));
            $academicYear = AcademicYear::firstOrCreate(
                [
                    'school_id'            => $school->getKey(),
                    'academic_year_name'   => $academicYear[0][0] . ' - ' . $academicYear[0][1],
                    'status_years'         => AcademicYear::STATUS_STARTED,
                    'year_start'           => Carbon::now()->year($academicYear[0][0]),
                    'year_end'             => Carbon::now()->year($academicYear[0][1])
                ]
            );

            session(['import_academic_year_id' => $academicYear->getKey()]);

            $kepsek = User::firstOrCreate([
                'name'         => $dataGeneral[5],
                'email'        => $dataGeneral[6],
                'school_id'    => $school->getKey(),
                'password'     => bcrypt('12345678')
            ]);
            // integrasi tabel staff
            $staff = new Staff();
            $staff->school_id = $school->getKey();
            $staff->user_id = $kepsek->id;
            $staff->name = $kepsek->name;
            $staff->phone_number = $dataGeneral[7];
            $staff->save();
            // Kepala Sekolah assign role
            $kepsek->assignRole(User::ROLE_KEPALA_SEKOLAH);

            if ($dataGeneral[5] != $dataGeneral[8]) {
                $admin = User::firstOrCreate(
                    [
                        'name'         => $dataGeneral[8],
                        'email'        => $dataGeneral[9],
                        'school_id'    => $school->getKey(),
                        'password'     => bcrypt('12345678')
                    ]
                );


                // integrasi tabel staff
                $staff = new Staff();
                $staff->school_id = $school->getKey();
                $staff->user_id = $admin->id;
                $staff->name = $admin->name;
                $staff->save();
                // assign Admin Sekolah
                $school->staff_id = $staff->getKey();
                $school->save();
                // Admin Sekolah assign role
                $admin->assignRole(User::ROLE_ADMIN_SEKOLAH);
                // verification & notification
                NewUser::createTokenFor($admin);

                
                session(['import_admin_id' => $admin->getKey()]);
            }

            // verification & notification
            NewUser::createTokenFor($kepsek);

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

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}