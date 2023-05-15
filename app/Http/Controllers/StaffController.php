<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StaffRequest;
use Illuminate\Support\Facades\Storage;

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

    public function show(Staff $staff)
    {
        $title = "Detail {$this->title}";
        return view('pages.staff.detail', compact('staff', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffRequest $request)
    {

        DB::beginTransaction();
        try {

            $staff                            = new Staff();
            $staff->school_id                 = $request->school_id;
            $staff->name                      = $request->name;
            $staff->gender                    = $request->gender;
            $staff->address                   = $request->address;
            $staff->dob                       = $request->dob;
            $staff->religion                  = $request->religion;
            $staff->phone_number              = $request->phone_number;
            $staff->family_card_number        = $request->family_card_number;
            $staff->nik                       = $request->nik;
            $staff->nip                       = $request->nip;
            $staff->nidn                      = $request->nidn;

            // Upload Staff Photo
            if ($request->hasFile('file_photo')) {
                $uploadedFile = $request->file('file_photo');
                if ($staff->file_photo) Storage::delete($staff->getRawOriginal('file_photo')); // Delete old photo
                $staff->file_photo = Storage::putFileAs('staff_photo', $uploadedFile, $uploadedFile->hashName());
            } else {
                $staff->file_photo = 'default-profile.jpg';
            }
            // End Upload Staff Photo

            // Upload Staff Birth Certificate
            if ($request->hasFile('file_birth_certificate')) {
                $uploadedFile = $request->file('file_birth_certificate');
                if ($staff->file_birth_certificate) Storage::delete($staff->getRawOriginal('file_birth_certificate')); // Delete old photo
                $staff->file_birth_certificate = Storage::putFileAs('staff_birth_certificate', $uploadedFile, $uploadedFile->hashName());
            }
            // End Upload Staff Birth Certificate

            // Upload Staff Family Card
            if ($request->hasFile('file_family_card')) {
                $uploadedFile = $request->file('file_family_card');
                if ($staff->file_family_card) Storage::delete($staff->getRawOriginal('file_family_card')); // Delete old photo
                $staff->file_family_card = Storage::putFileAs('staff_family_card', $uploadedFile, $uploadedFile->hashName());
            }
            // End Upload Staff Family Card

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

            $staff->school_id                 = $request->school_id;
            $staff->name                      = $request->name;
            $staff->gender                    = $request->gender;
            $staff->address                   = $request->address;
            $staff->dob                       = $request->dob;
            $staff->religion                  = $request->religion;
            $staff->phone_number              = $request->phone_number;
            $staff->family_card_number        = $request->family_card_number;
            $staff->nik                       = $request->nik;
            $staff->nip                       = $request->nip;
            $staff->nidn                      = $request->nidn;

            // Upload Staff Photo
            if ($request->hasFile('file_photo')) {
                $uploadedFile = $request->file('file_photo');
                if ($staff->file_photo) Storage::delete($staff->getRawOriginal('file_photo')); // Delete old photo
                $staff->file_photo = Storage::putFileAs('staff_photo', $uploadedFile, $uploadedFile->hashName());
            } else {
                $staff->file_photo = 'default-profile.jpg';
            }
            // End Upload Staff Photo

            // Upload Staff Birth Certificate
            if ($request->hasFile('file_birth_certificate')) {
                $uploadedFile = $request->file('file_birth_certificate');
                if ($staff->file_birth_certificate) Storage::delete($staff->getRawOriginal('file_birth_certificate')); // Delete old photo
                $staff->file_birth_certificate = Storage::putFileAs('staff_birth_certificate', $uploadedFile, $uploadedFile->hashName());
            }
            // End Upload Staff Birth Certificate

            // Upload Staff Family Card
            if ($request->hasFile('file_family_card')) {
                $uploadedFile = $request->file('file_family_card');
                if ($staff->file_family_card) Storage::delete($staff->getRawOriginal('file_family_card')); // Delete old photo
                $staff->file_family_card = Storage::putFileAs('staff_family_card', $uploadedFile, $uploadedFile->hashName());
            }
            // End Upload Staff Family Card

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
