<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AssignClassroomStaffRequest;
use App\Models\ClassroomStaff;
use App\Models\Staff;
use Exception;

class AssignClassroomStaffController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data['title'] = "Tetapkan Wali Kelas";
        $data['academy_years'] = AcademicYear::whereIn('status_years', [
            AcademicYear::STATUS_STARTED,
            AcademicYear::STATUS_REGISTRATION
        ])->orderBy('status_years', 'desc')->get();
        return view('pages.assign-classroom-staff.index', $data);
    }

    public function create()
    {
        $data['title'] = "Tetapkan Wali Kelas";
        return view('pages.assign-classroom-staff.create', $data);
    }

    public function show(ClassroomStaff $classroomStaff)
    {
    }

    public function store(AssignClassroomStaffRequest $request)
    {
        DB::beginTransaction();

        try {
            $classroom = Classroom::find($request->classroom_id);
            $classroom->staff()->attach([$request->id]);
            $classroom->save();
            DB::commit();
        } catch (Exception $e) {
            $msg =  $e->getCode() == 0 ? $e->getMessage() : "Gagal Tetapkan Wali Kelas";
            return redirect()
                ->route('assign-classroom-staff.index')
                ->with('classroom_id', $request->classroom_id)
                ->withToastError($msg);
        }


        return redirect()
            ->route('assign-classroom-staff.index')
            ->withToastSuccess('Berhasil Tetapkan Wali Kelas');
    }

    public function destroy(AssignClassroomStaffRequest $request)
    {


        DB::beginTransaction();
        try {
            $classroom = Classroom::find($request->classroom_old);
            $classroom->staff()->detach([$request->id]);
            $classroom->save();

            $classroom = Classroom::find($request->classroom_id);
            $classroom->staff()->attach([$request->id]);
            $classroom->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->route('assign-classroom-staff.index')
                ->with('classroom_id', $request->classroom_id)
                ->withToastError('Gagal Hapus Wali Kelas ');
        }

        return redirect()
            ->route('assign-classroom-staff.index')
            ->withToastSuccess('Berhasil Ubah Wali Kelas');
    }
}
