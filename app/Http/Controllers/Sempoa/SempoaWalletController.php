<?php

namespace App\Http\Controllers\Sempoa;

use App\Http\Controllers\Controller;
use App\Models\SempoaWallet;
use App\Models\Wallet;
use Illuminate\Http\Request;

class SempoaWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Konfigurasi Dompet';
        $data['wallets'] = Wallet::all();
        return view('pages.sempoa.wallet', $data);
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
    public function show(SempoaWallet $sempoaWallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SempoaWallet $sempoaWallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SempoaWallet $sempoaWallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SempoaWallet $sempoaWallet)
    {
        //
    }
}
