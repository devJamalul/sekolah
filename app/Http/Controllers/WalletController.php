<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\WalletRequest;

class WalletController extends Controller
{
    /**e
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Dompet";
        return view('pages.wallet.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Dompet";
        return view('pages.wallet.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WalletRequest $request)
    {
        DB::beginTransaction();

        try {
            
            $wallet                 = new Wallet();
            $wallet->school_id      = session('school_id');
            $wallet->name           = $request->name;
            $wallet->init_value     = $request->init_value;
            $wallet->last_balance   = 0;
            $wallet->save();

            DB::commit();
            

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('wallet.index')->withToastError('Eror Simpan Dompet!');
        }

        return redirect()->route('wallet.index')->withToastSuccess('Berhasil Simpan Dompet!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        $title = 'Ubah Dompet';
        return view('pages.wallet.edit', compact('wallet', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WalletRequest $request, Wallet $wallet)
    {
        DB::beginTransaction();

        try {
            
            $wallet->school_id  = session('school_id');
            $wallet->name       = $request->name;
            $wallet->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('wallet.index')->withToastError('Eror Simpan Dompet!');
        }

        return redirect()->route('wallet.index')->withToastSuccess('Berhasil Simpan Dompet!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
       DB::beginTransaction();

       try {
        
        $wallet->delete();
        DB::commit();

        return response()->json([
            'msg' => 'Berhasil Hapus Dompet!'
        ], 200);

       } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Eror Hapus Dompet!'
            ]);
       }
    }
}
