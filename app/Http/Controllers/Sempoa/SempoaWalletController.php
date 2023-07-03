<?php

namespace App\Http\Controllers\Sempoa;

use App\Actions\Sempoa\CheckAccount;
use App\Actions\Sempoa\GetAccount;
use App\Http\Controllers\Controller;
use App\Models\SempoaConfiguration;
use App\Models\SempoaWallet;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SempoaWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Konfigurasi Dompet';
        $data['wallets'] = Wallet::all();
        $data['config'] = SempoaConfiguration::first();
        $data['accounts'] = [];
        if ($data['config']) $data['accounts'] = GetAccount::run();
        return view('pages.sempoa.wallet', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->wallet_id as $key => $id) {
                SempoaWallet::updateOrCreate(
                    [
                        'school_id' => session('school_id'),
                        'wallet_id' => $id
                    ],
                    [
                        'account' => self::checkAccount($request->wallet[$key])
                    ]
                );
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('sempoa-wallet.index')->withInput()->withToastError("Ops! " . $th->getMessage());
        }
        return to_route('sempoa-wallet.index')->withInput()->withToastSuccess('Konfigurasi berhasil disimpan!');
    }

    protected function checkAccount($account)
    {
        if (is_null($account)) return null;

        try {
            $res = CheckAccount::run($account);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }

        return $res;
    }
}
