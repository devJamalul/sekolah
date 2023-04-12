<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;

class StaffController extends Controller
{

    protected $title = 'Staff';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = $this->title;
        return view('pages.staff.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah {$this->title}";
        return view('pages.staff.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffRequest $request)
    {

        DB::beginTransaction();
        try {

            $staff            = new Staff();
            $staff->school_id = $request->school_id;
            $staff->name      = $request->name;
            $staff->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('staff.create')->withToastError("Ops Gagal Tambah {$this->title}!");
        }

        return redirect()->route('staff.index')->withToastSuccess("Tambah {$this->title} Berhasil!");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {

        $title = "Ubah {$this->title}";
        return view('pages.staff.edit', compact('staff', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StaffRequest $request, Staff $staff)
    {

        DB::beginTransaction();
        try {

            $staff->school_id = $request->school_id;
            $staff->name      = $request->name;
            $staff->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('staff.edit', $tuitionType->id)->withToastError("Ops Gagal ubah {$this->title}!");
        }

        return redirect()->route('staff.index')->withToastSuccess("Ubah {$this->title} Berhasil!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {

        DB::beginTransaction();
        try {

            $staff->delete();
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
