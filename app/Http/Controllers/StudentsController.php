<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentsRequest;
use App\Imports\StudentsImport;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentTuition;
use App\Models\StudentTuitionMaster;
use App\Models\TuitionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\isNull;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => "Siswa"
        ];

        return view('pages.students.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'tuition_types' => TuitionType::where('school_id', session('school_id'))->get(),
            'title' => "Tambah Siswa",
        ];

        return view('pages.students.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentsRequest $request)
    {   

        if (
            is_null($request->guardian_name) && is_null($request->guardian_phone_number) && is_null($request->guardian_address) &&
            is_null($request->father_name) && is_null($request->father_phone_number) && is_null($request->father_address) &&
            is_null($request->mother_name) && is_null($request->mother_phone_number) && is_null($request->mother_address)
        ) {
            return redirect()->back()->withInput()->withToastError('Ayah/Ibu/Wali harus diisi');
        }

        try {
            DB::beginTransaction();

            // Save Student
                $student                            = new Student;
                $student->school_id                 = session('school_id');

                $student->name                      = $request->name;
                $student->email                     = $request->email;
                $student->gender                    = $request->gender;
                $student->address                   = $request->address;
                $student->dob                       = $request->dob;
                $student->religion                  = $request->religion;
                $student->phone_number              = $request->phone_number;
                $student->family_card_number        = $request->family_card_number;
                $student->nik                       = $request->nik;
                $student->nis                       = $request->nis;
                $student->nisn                      = $request->nisn;

                $student->father_name               = $request->father_name;
                $student->father_phone_number       = $request->father_phone_number;
                ($request->father_address_checkbox)
                    ? $student->father_address          = $request->address
                    : $student->father_address          = $request->father_address;

                $student->mother_name                   = $request->mother_name;
                $student->mother_phone_number           = $request->mother_phone_number;
                ($request->mother_address_checkbox)
                    ? $student->mother_address          = $request->address
                    : $student->mother_address          = $request->mother_address;

                $student->guardian_name                 = $request->guardian_name;
                $student->guardian_phone_number         = $request->guardian_phone_number;
                ($request->guardian_address_checkbox)
                    ? $student->guardian_address        = $request->address
                    : $student->guardian_address        = $request->guardian_address;

                // Upload Student's Photo
                if ($request->hasFile('file_photo')) {
                    $uploadedFile = $request->file('file_photo');
                    if ($student->file_photo) Storage::delete($student->getRawOriginal('file_photo')); // Delete old photo
                    $student->file_photo = Storage::putFileAs('student_photo', $uploadedFile, $uploadedFile->hashName());
                } else {
                    $student->file_photo = 'default-profile.jpg';
                }
                // End Upload Student's Photo

                // Upload Student's Birth Certificate
                if ($request->hasFile('file_birth_certificate')) {
                    $uploadedFile = $request->file('file_birth_certificate');
                    if ($student->file_birth_certificate) Storage::delete($student->getRawOriginal('file_birth_certificate')); // Delete old photo
                    $student->file_birth_certificate = Storage::putFileAs('student_birth_certificate', $uploadedFile, $uploadedFile->hashName());
                }
                // End Upload Student's Birth Certificate

                // Upload Student's Family Card
                if ($request->hasFile('file_family_card')) {
                    $uploadedFile = $request->file('file_family_card');
                    if ($student->file_family_card) Storage::delete($student->getRawOriginal('file_family_card')); // Delete old photo
                    $student->file_family_card = Storage::putFileAs('student_family_card', $uploadedFile, $uploadedFile->hashName());
                }
                // End Upload Student's Family Card

                $student->save();
            // End Save Student

            DB::commit();

            return redirect()->route('students.index')->withToastSuccess('Berhasil menambahkan data siswa!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError('Ops, ada kesalahan saat menambahkan data siswa!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $data = [
            'student' => $student,
            'academic_years' => AcademicYear::where('school_id', session('school_id'))->orderByDesc('created_at')->get(),
            'title' => "Ubah Data Siswa",
        ];

        return view('pages.students.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentsRequest $request, Student $student)
    {
        try {
            DB::beginTransaction();
            
            // Update Student
                $student->name                      = $request->name;
                $student->email                     = $request->email;
                $student->gender                    = $request->gender;
                $student->address                   = $request->address;
                $student->dob                       = $request->dob;
                $student->religion                  = $request->religion;
                $student->phone_number              = $request->phone_number;
                $student->family_card_number        = $request->family_card_number;
                $student->nik                       = $request->nik;
                $student->nis                       = $request->nis;
                $student->nisn                      = $request->nisn;

                $student->father_name               = $request->father_name;
                $student->father_phone_number       = $request->father_phone_number;
                $student->father_address            = $request->father_address;

                $student->mother_name               = $request->mother_name;
                $student->mother_phone_number       = $request->mother_phone_number;
                $student->mother_address            = $request->mother_address;

                $student->guardian_name             = $request->guardian_name;
                $student->guardian_phone_number     = $request->guardian_phone_number;
                $student->guardian_address          = $request->guardian_address;

                // Upload Student's Photo
                if ($request->hasFile('file_photo')) {
                    $uploadedFile = $request->file('file_photo');
                    if ($student->file_photo) Storage::delete($student->getRawOriginal('file_photo')); // Delete old photo
                    $student->file_photo = Storage::putFileAs('student_photo', $uploadedFile, $uploadedFile->hashName());
                } else {
                    $student->file_photo = 'default-profile.jpg';
                }
                // End Upload Student's Photo

                // Upload Student's Birth Certificate
                if ($request->hasFile('file_birth_certificate')) {
                    $uploadedFile = $request->file('file_birth_certificate');
                    if ($student->file_birth_certificate) Storage::delete($student->getRawOriginal('file_birth_certificate')); // Delete old photo
                    $student->file_birth_certificate = Storage::putFileAs('student_birth_certificate', $uploadedFile, $uploadedFile->hashName());
                }
                // End Upload Student's Birth Certificate

                // Upload Student's Family Card
                if ($request->hasFile('file_family_card')) {
                    $uploadedFile = $request->file('file_family_card');
                    if ($student->file_family_card) Storage::delete($student->getRawOriginal('file_family_card')); // Delete old photo
                    $student->file_family_card = Storage::putFileAs('student_family_card', $uploadedFile, $uploadedFile->hashName());
                }
                // End Upload Student's Family Card

                $student->save();
            // End Update Student


            DB::commit();

            return redirect()->route('students.index')->withToastSuccess('Berhasil mengubah data siswa!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError('Ops, ada kesalahan saat mengubah data siswa!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            $student->status = Student::STATUS_INACTIVE;
            $student->save();
            $student->delete();

            return response()->json([
                'msg' => 'Berhasil menghapus data siswa!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal menghapus data siswa!'
            ], 400);
        }
    }
    
    public function tuitionMaster(Student $student)
    {
        $data = [
            'student' => $student,
            'title' => "Biaya Khusus Siswa",
        ];

        return view('pages.students.tuition-master', $data);
    }

    public function importStudent()
    {
        $academicYears = AcademicYear::where('school_id', session('school_id'))->get();

        $data = [
            'academic_years' => $academicYears,
            'title' => "Impor Data Siswa",
        ];

        return view('pages.students.import', $data);
    }

    public function importStudentByExcel(Request $request)
    {
        try {
            Excel::import(new StudentsImport, $request->file('excel_file'));
            return redirect()->route('students.index')->withToastSuccess('Berhasil mengimpor data siswa!');
        } catch (ValidationException $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError($ex->errors());
        } catch (Exception $ex) {
            DB::rollBack();
            dd($ex);
            return redirect()->back()->withInput()->withToastError("Ops, ada kesalahan saat mengimpor data siswa!");
        }
    }
}
