<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolSelectorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): void
    {
        try {
            $school = School::findOrFail($request->school_selector);
            $this->save($school);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage(), [
                'action' => 'Ubah selector sekolah',
                'user' => auth()->user()->name,
                'school_id' => $request->school_selector
            ]);
        }
    }

    protected function save(School $school): void
    {
        try {
            $key = "school_id";
            session([$key => $school->getKey()]);

            $academicYear = AcademicYear::active()->first();

            if (!is_null($academicYear)) {
                session(['academic_year_id' => $academicYear->id]);
                session(['academic_year_name' => $academicYear->academic_year_name]);
            } else {
                session()->forget(['academic_year_id', 'academic_year_name']);
            }

            $ppdb = AcademicYear::PPDB()->first();

            if (!is_null($ppdb)) {
                session(['ppdb_academic_year_id' => $ppdb->id]);
                session(['ppdb_academic_year_name' => $ppdb->academic_year_name]);
            } else {
                session()->forget(['ppdb_academic_year_id', 'ppdb_academic_year_name']);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage(), [
                'action' => 'Simpan selector sekolah',
                'user' => auth()->user()->name,
                'school' => $school
            ]);
        }
    }
}
