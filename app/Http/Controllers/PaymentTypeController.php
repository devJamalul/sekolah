<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PaymentTypeRequest;

class PaymentTypeController extends Controller
{

    protected $title = "Tipe Pembayaran";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = $this->title;
        return view('pages.payment-type.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah {$this->title}";
        return view('pages.payment-type.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentTypeRequest $request)
    {

        DB::beginTransaction();
        try {

            $paymentType            = new PaymentType();
            $paymentType->school_id = $request->school_id;
            $paymentType->name      = $request->name;
            $paymentType->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('payment-type.create')->withToastError("Ops Gagal Tambah {$this->title}!");
        }

        return redirect()->route('payment-type.index')->withToastSuccess("Tambah {$this->title} Berhasil!");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentType $paymentType)
    {
        $title = "Ubah {$this->title}";
        return view('pages.payment-type.edit', compact('paymentType', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentTypeRequest $request, PaymentType $paymentType)
    {
        //
        DB::beginTransaction();
        try {

            $paymentType->school_id = $request->school_id;
            $paymentType->name      = $request->name;
            $paymentType->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('payment-type.edit', $paymentType->id)->withToastError("Ops Gagal ubah {$this->title}!");
        }

        return redirect()->route('payment-type.index')->withToastSuccess("Ubah {$this->title} Berhasil!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentType $paymentType)
    {

        DB::beginTransaction();
        try {

            $paymentType->delete();
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

        //
    }
}
