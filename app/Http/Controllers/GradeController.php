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

            $grade              = new Grade();
            $grade->school_id   = session('school_id');
            $grade->grade_name  = $request->grade_name;
            $grade->save();

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

            $grade->school_id   = session('school_id');
            $grade->grade_name  = $request->grade_name;
            $grade->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('grade.index')->withToastError('Eror Simpan Tingkat!');
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
