<?php

namespace App\Http\Controllers;

use App\Models\Tuition;
use App\Notifications\TuitionApprovalNotification;
use App\Notifications\TuitionRejectionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TuitionApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => "Persetujuan Uang Sekolah"
        ];

        return view('pages.tuition-approval.index', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tuition $tuition_approval)
    {
        $data = [
            'title' => "Persetujuan Uang Sekolah",
            'tuition' => $tuition_approval,
        ];
        return view('pages.tuition-approval.detail', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tuition $tuition_approval)
    {
        DB::beginTransaction();
        try {
            switch ($request->action) {
                case 'approve':
                    $tuition_approval->status = Tuition::STATUS_APPROVED;
                    $tuition_approval->approval_by = Auth::user()->id;
                    $tuition_approval->approved_at = now();
                    break;
                case 'reject':

                    if ($request->reject_reason == '') {
                        return redirect()->back()->withToastError('Ops, alasan penolakan wajib diisi!');
                    }

                    $tuition_approval->status = Tuition::STATUS_REJECTED;
                    $tuition_approval->reject_reason  = $request->reject_reason;
                    $tuition_approval->rejected_at = now();
                    $tuition_approval->rejected_by = Auth::user()->id;
                    break;
            }
            $tuition_approval->save();

            DB::commit();

            // Notification
            switch ($request->action) {
                case 'approve':
                    $tuition_approval->requested_by->notify(new TuitionApprovalNotification($tuition_approval));
                    break;
                    case 'reject':
                        $tuition_approval->requested_by->notify(new TuitionRejectionNotification($tuition_approval));
                    break;
            }

            return redirect()->route('tuition-approval.index')->withToastSuccess('Berhasil mengubah status!');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return redirect()->back()->withToastError('Ops, ada kesalahan saat mengubah Status! ' . $th->getMessage());
        }
    }
}
