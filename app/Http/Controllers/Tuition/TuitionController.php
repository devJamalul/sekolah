<?php

namespace App\Http\Controllers\Tuition;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Grade;
use App\Models\Tuition;
use App\Models\TuitionType;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TuitionRequest;
use App\Notifications\TuitionApprovalNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class TuitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = "Uang Sekolah";
        return view('pages.tuition.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Uang Sekolah";
        $tuitionTypes = TuitionType::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('academic_year_name')->whereIn('status_years', [AcademicYear::STATUS_STARTED, AcademicYear::STATUS_REGISTRATION])->get();
        $grades = Grade::orderBy('grade_name')->get();
        $users = User::where('school_id', session('school_id'))->whereHas('roles', function ($q) {
            $q->whereIn('name', [
                User::ROLE_ADMIN_SEKOLAH,
                User::ROLE_ADMIN_YAYASAN,
                User::ROLE_TATA_USAHA,
                User::ROLE_BENDAHARA,
                User::ROLE_KEPALA_SEKOLAH,
            ]);
        })->get();
        return view('pages.tuition.create', compact('tuitionTypes', 'academicYears', 'grades', 'title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TuitionRequest $request)
    {
        DB::beginTransaction();
        try {
            $cek = Tuition::where([
                'school_id' => session('school_id'),
                'tuition_type_id' => $request->tuition_type_id,
                'academic_year_id' => $request->academic_year_id,
                'grade_id' => $request->grade_id,
            ])->exists();
            if ($cek) {
                throw new \Exception('Uang Sekolah sudah ada!');
            }

            $tuition                    = new Tuition();
            $tuition->school_id         = session('school_id');
            $tuition->tuition_type_id   = $request->tuition_type_id;
            $tuition->academic_year_id  = $request->academic_year_id;
            $tuition->grade_id          = $request->grade_id;
            $tuition->price             = formatAngka($request->price);
            $tuition->status            = Tuition::STATUS_PENDING;
            $tuition->request_by        = Auth::id();
            $tuition->save();
            DB::commit();

            // sesuai arahan Pak Ivan. Data tetap disimpan meski ada masalah dalam mengirimkan notifikasi.
            $headmasters = User::where('school_id', session('school_id'))->role('kepala sekolah')->get();
            foreach ($headmasters as $headmaster) {
                $headmaster->notify(new TuitionApprovalNotification($tuition));
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return redirect()->route('tuition.index')->withToastError('Peringatan! ' . $th->getMessage());
        }

        return redirect()->route('tuition.index')->withToastSuccess('Berhasil Simpan Uang Sekolah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tuition $tuition)
    {
        return self::edit($tuition);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tuition $tuition)
    {
        try {
            if ($tuition->status != Tuition::STATUS_PENDING) {
                throw new \Exception('Uang Sekolah sudah tidak bisa diubah');
            }
            $title = 'Ubah Uang Sekolah';
            $tuitionTypes = TuitionType::orderBy('name')->get();
            $academicYears = AcademicYear::orderByDesc('academic_year_name')->get();
            $grades = Grade::orderBy('grade_name')->get();
            $users = User::where('school_id', session('school_id'))->whereHas('roles', function ($q) {
                $q->whereIn('name', [
                    User::ROLE_ADMIN_SEKOLAH,
                    User::ROLE_ADMIN_YAYASAN,
                    User::ROLE_TATA_USAHA,
                    User::ROLE_BENDAHARA,
                    User::ROLE_KEPALA_SEKOLAH,
                ]);
            })->get();
        } catch (\Throwable $th) {
            return redirect()->route('tuition.index')->withToastError('Peringatan! ' . $th->getMessage());
        }

        return view('pages.tuition.edit', compact('tuitionTypes', 'tuition', 'academicYears', 'grades', 'title', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TuitionRequest $request, Tuition $tuition)
    {
        DB::beginTransaction();
        try {
            if ($tuition->status != Tuition::STATUS_PENDING) {
                throw new \Exception('Uang Sekolah sudah tidak bisa diubah');
            }

            $cek = Tuition::where([
                'school_id' => session('school_id'),
                'tuition_type_id' => $request->tuition_type_id,
                'academic_year_id' => $request->academic_year_id,
                'grade_id' => $request->grade_id,
            ])
                ->whereNot(function (Builder $query) use ($tuition) {
                    $query->where('id', $tuition->getKey());
                })
                ->exists();
            if ($cek) {
                throw new \Exception('Uang Sekolah sudah ada!');
            }

            $tuition->school_id             = session('school_id');
            $tuition->tuition_type_id       = $request->tuition_type_id;
            $tuition->academic_year_id      = $request->academic_year_id;
            $tuition->grade_id              = $request->grade_id;
            $tuition->price                 = formatAngka($request->price);
            $tuition->request_by            = Auth::id();
            $tuition->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return redirect()->route('tuition.index')->withToastError('Peringatan! ' . $th->getMessage());
        }

        return redirect()->route('tuition.index')->withToastSuccess('Berhasil Simpan Biaya!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tuition $tuition)
    {
        DB::beginTransaction();
        try {
            if ($tuition->status != Tuition::STATUS_PENDING) {
                throw new \Exception('Biaya sudah tidak bisa dihapus');
            }

            $tuition->delete();
            DB::commit();

            return response()->json([
                'msg' => 'Berhasil Hapus Biaya!'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return response()->json([
                'msg' => 'Peringatan! ' . $th->getMessage()
            ]);
        }
    }
}
