<?php

namespace App\Http\Controllers;

use App\Models\Tuition;
use App\Notifications\TuitionApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuitionApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => "Persetujuan Biaya"
        ];

        return view('pages.tuition-approval.index', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tuition $tuition_approval)
    {
        $data = [
            'title' => "Persetujuan Biaya",
            'tuition' => $tuition_approval,
        ];
        return view('pages.tuition-approval.detail', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tuition $tuition_approval)
    {
        try {
            switch ($request->action) {
                case 'approve':
                    $tuition_approval->status = Tuition::STATUS_APPROVED;
                    $tuition_approval->approval_by = Auth::user()->id;
                    break;
                case 'reject':

                    if ($request->reject_reason == '') {
                        return redirect()->back()->withToastError('Ops,Alasan Penolakan Wajib Diisi !');
                    }

                    $tuition_approval->status = Tuition::STATUS_REJECTED;
                    $tuition_approval->reject_reason  = $request->reject_reason;
                    break;
            }
            $tuition_approval->save();

            // Notification
            $tuition_approval->requested_by->notify(new TuitionApprovalNotification($tuition_approval));

            return redirect()->route('tuition-approval.index')->withToastSuccess('Berhasil mengubah Status!');
        } catch (\Throwable $th) {
            return redirect()->back()->withToastError('Ops, ada kesalahan saat mengubah Status!');
        }
    }
}
