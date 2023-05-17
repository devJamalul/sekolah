<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
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
        $data['title'] = "Tambah Dompet";
        $data['danabos'] = Wallet::where('danabos', 1)->count();
        return view('pages.wallet.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WalletRequest $request)
    {
        DB::beginTransaction();
        $DanaBosCount = Wallet::where('danabos', 1)->count();

        try {
            $wallet                 = new Wallet();
            $wallet->school_id      = session('school_id');
            $wallet->name           = $request->name;
            $wallet->init_value     = formatAngka($request->init_value);
            if ($request->has('danabos') and $DanaBosCount == 0) {
                $wallet->danabos = true;
            }
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
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        $data['title'] = 'Ubah Dompet';
        $data['wallet'] = $wallet;
        $data['danabos'] = Wallet::where('danabos', 1)->count();
        return view('pages.wallet.edit', $data);
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
            $wallet->danabos = $request->danabos ?? 0;
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

       if ($wallet->balance > 0) {
            return response()->json([
                'msg' => 'Tidak bisa menghapus Dompet!'
            ]);
       }

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
