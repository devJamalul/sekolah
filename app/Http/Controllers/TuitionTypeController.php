<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\TuitionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TuitionTypeRequest;

class TuitionTypeController extends Controller
{

    protected $title = 'Tipe Biaya';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = $this->title;
        return view('pages.tuition-type.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah {$this->title}";
        return view('pages.tuition-type.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TuitionTypeRequest $request)
    {

        DB::beginTransaction();
        try {

            $tuitionType            = new TuitionType();
            $tuitionType->school_id = $request->school_id;
            $tuitionType->name      = $request->name;
            $tuitionType->recurring = $request->generatable ?? false;
            $tuitionType->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('tuition-type.create')->withToastError("Ops Gagal Tambah {$this->title}!");
        }

        return redirect()->route('tuition-type.index')->withToastSuccess("Tambah {$this->title} Berhasil!");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TuitionType $tuitionType)
    {

        $title = "Ubah {$this->title}";
        return view('pages.tuition-type.edit', compact('tuitionType', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TuitionTypeRequest $request, TuitionType $tuitionType)
    {

        DB::beginTransaction();
        try {

            $tuitionType->school_id = $request->school_id;
            $tuitionType->name      = $request->name;
            $tuitionType->recurring = $request->generatable ?? false;
            $tuitionType->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('tuition-type.edit', $tuitionType->id)->withToastError("Ops Gagal ubah {$this->title}!");
        }


        return redirect()->route('tuition-type.index')->withToastSuccess("Ubah {$this->title} Berhasil!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TuitionType $tuitionType)
    {

        DB::beginTransaction();
        try {

            $tuitionType->delete();
            DB::commit();
            return response()->json([
                'msg' => "Berhasil Hapus {$this->title}"
            ], 200);
        } catch (\Throwable $th) {

            DB::rollback();
            return response()->json([
                'msg' => "Ops Hapus {$this->title} Gagal!"
            ], 400);
        }
    }
}
