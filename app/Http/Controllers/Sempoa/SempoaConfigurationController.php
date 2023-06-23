<?php

namespace App\Http\Controllers\Sempoa;

use App\Actions\Sempoa\CheckAccount;
use App\Actions\Sempoa\CheckToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\SempoaConfigurationStoreRequest;
use App\Http\Requests\SempoaConfigurationUpdateRequest;
use App\Models\SempoaConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SempoaConfigurationController extends Controller
{
    public function index()
    {
        $data['title'] = 'Konfigurasi Sempoa';
        $data['config'] = SempoaConfiguration::first();
        return view('pages.sempoa.configuration', $data);
    }

    public function store(SempoaConfigurationStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            CheckToken::run($request->token);
            $save = SempoaConfiguration::firstOrNew([
                'school_id' => session('school_id'),
            ]);
            $save->token = $request->token;
            $save->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('sempoa-configuration.index')->withInput()->withToastError("Ops! " . $th->getMessage());
        }
        return to_route('sempoa-configuration.index')->withInput()->withToastSuccess('Konfigurasi berhasil disimpan!');
    }

    public function update(SempoaConfigurationUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            CheckToken::run($request->token);
            $save = SempoaConfiguration::firstOrNew([
                'school_id' => session('school_id'),
            ]);
            $save->token = $request->token;
            $save->tuition_debit_account = self::checkAccount($request->tuition_debit_account);
            $save->tuition_credit_account = self::checkAccount($request->tuition_credit_account);
            $save->invoice_debit_account = self::checkAccount($request->invoice_debit_account);
            $save->invoice_credit_account = self::checkAccount($request->invoice_credit_account);
            $save->expense_debit_account = self::checkAccount($request->expense_debit_account);
            $save->expense_credit_account = self::checkAccount($request->expense_credit_account);
            $save->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('sempoa-configuration.index')->withInput()->withToastError("Ops! " . $th->getMessage());
        }
        return to_route('sempoa-configuration.index')->withInput()->withToastSuccess('Konfigurasi berhasil disimpan!');
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
