<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GradeRequest;
use App\Models\Grade;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = "Tingkatan";
        return view('pages.grade.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Tingkatan";
        $schools = School::all();
        return view('pages.grade.create', compact('schools', 'title'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(GradeRequest $request)
    {
        //
        DB::beginTransaction();

        try {
            $grades = explode(",", $request->grade_name);

            foreach ($grades as $item) {
                $item = trim($item);
                $cek = Grade::firstWhere([
                    'school_id' => session('school_id'),
                    'grade_name' => $item
                ]);
                if (!$item or $item == '' or is_null($item) or $cek) continue;

                $grade              = new Grade();
                $grade->school_id   = session('school_id');
                $grade->grade_name  = $item;
                $grade->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('grade.index')->withToastError('Eror Simpan Tingkat!');
        }

        return redirect()->route('grade.index')->withToastSuccess('Berhasil Simpan Tingkat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {

        $schools = School::all();
        $title = "Ubah Tingkatan";
        return view('pages.grade.edit', compact('schools', 'grade', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GradeRequest $request, Grade $grade)
    {
        DB::beginTransaction();

        try {
            if (str_contains($request->grade_name, ',')) {
                throw new \Exception('Tidak boleh mengandung koma.');
            }

            $grade->school_id   = session('school_id');
            $grade->grade_name  = $request->grade_name;
            $grade->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('grade.index')->withToastError('Eror Simpan Tingkat! ' . $th->getMessage());
        }

        return redirect()->route('grade.index')->withToastSuccess('Berhasil Simpan Tingkat!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        DB::beginTransaction();
        try {

            $grade->delete();
            DB::commit();

            return response()->json([
                'msg' => 'Berhasil Hapus Tingkat!'
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response()->json([
                'msg' => 'Eror Hapus Tingkat!'
            ]);
        }
    }
}
