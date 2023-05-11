<?php

namespace App\Http\Controllers;

use App\Models\Tuition;
use App\Models\User;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tuition $tuition_approval)
    {
        try {
            switch ($request->action) {
                case 'approve':
                    $tuition_approval->approval_by = Auth::user()->id;
                    break;
                case 'reject':
                    $tuition_approval->delete();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
