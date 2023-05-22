<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\School;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AcademyYearRequest;

class AcademyYearController extends Controller
{
    private $title = "Tahun Akademik";

    private $academyYearStatus = [
        AcademicYear::STATUS_STARTED => 'Aktif',
        AcademicYear::STATUS_REGISTRATION => 'Register',
        AcademicYear::STATUS_CLOSED => 'Ditutup'
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = "{$this->title}";
        return view('pages.academy-year.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academyYearStatus = $this->academyYearStatus;
        $title = "Tambah {$this->title}";
        return view('pages.academy-year.create', compact('title', 'academyYearStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademyYearRequest $request)
    {
        DB::beginTransaction();
        try {
            $academyYear                     = new AcademicYear();
            $academyYear->year_start         = $request->year_start;
            $academyYear->year_end           = $request->year_end;
            $academyYear->school_id          = $request->school_id;
            $academyYear->academic_year_name = $request->academic_year_name;
            $academyYear->status_years       = $request->status_years;
            $academyYear->save();

            $this->updateStatusYearsClosed($academyYear, $request->status_years);
            $this->updateSessionStatusActive($academyYear);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('academy-year.index')->withToastError("Ops Gagal Tambah {$this->title}!");
        }

        return redirect()->route('academy-year.index')->withToastSuccess("Tambah {$this->title} Berhasil!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academyYear)
    {
        $title = "Ubah {$this->title}";
        $academyYearStatus = $this->academyYearStatus;
        return view('pages.academy-year.edit', compact('academyYear', 'title', 'academyYearStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AcademyYearRequest $request, AcademicYear $academyYear)
    {
        DB::beginTransaction();
        try {
            $academyYear->school_id          = $request->school_id;
            $academyYear->year_start         = $request->year_start;
            $academyYear->year_end           = $request->year_end;
            $academyYear->academic_year_name = $request->academic_year_name;
            $academyYear->status_years       = $request->status_years;
            $academyYear->save();

            if ($academyYear->wasChanged('status_years')) {
                $this->updateStatusYearsClosed($academyYear, $request->status_years);
            }

            $this->updateSessionStatusActive($academyYear);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('academy-year.index')->withToastError("Ops Gagal ubah {$this->title}!");
        }

        return redirect()->route('academy-year.index')->withToastSuccess("Ubah {$this->title} Berhasil!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academyYear)
    {
        DB::beginTransaction();
        try {
            if ($academyYear->status_years === AcademicYear::STATUS_STARTED) {
                session()->forget(['academic_year_id', 'academic_year_name']);
            }

            if ($academyYear->status_years === AcademicYear::STATUS_REGISTRATION) {
                session()->forget(['ppdb_academic_year_id', 'ppdb_academic_year_name']);
            }

            $academyYear->delete();
            DB::commit();
            return response()->json([
                'msg' => "Berhasil Hapus {$this->title}"
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'msg' => "Ops Gagal Hapus {$this->title}!"
            ], 400);
        }
    }

    private function updateStatusYearsClosed(AcademicYear $academicYear, $status)
    {
        AcademicYear::where('status_years', $status)
            ->where('id', '<>', $academicYear->getKey())
            ->update(['status_years' => AcademicYear::STATUS_CLOSED]);
    }

    private function updateSessionStatusActive(AcademicYear $academyYear)
    {
        if ($academyYear->status_years === AcademicYear::STATUS_STARTED) {
            session(['academic_year_id' => $academyYear->id]);
            session(['academic_year_name' => $academyYear->academic_year_name]);
        }

        if ($academyYear->status_years === AcademicYear::STATUS_REGISTRATION) {
            session(['ppdb_academic_year_id' => $academyYear->id]);
            session(['ppdb_academic_year_name' => $academyYear->academic_year_name]);
        }

        // cek void
        $tahun_aktif = AcademicYear::where('status_years', AcademicYear::STATUS_STARTED)->first();
        if (!$tahun_aktif) {
            session()->forget(['academic_year_id', 'academic_year_name']);
        }

        $tahun_ppdb = AcademicYear::where('status_years', AcademicYear::STATUS_REGISTRATION)->first();
        if (!$tahun_ppdb) {
            session()->forget(['ppdb_academic_year_id', 'ppdb_academic_year_name']);
        }
    }
}
