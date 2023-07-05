<?php

namespace App\Http\Controllers\Sempoa;

use App\Actions\Sempoa\CheckAccount;
use App\Actions\Sempoa\CheckToken;
use App\Actions\Sempoa\GetAccount;
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
        $data['accounts'] = [];
        if ($data['config']) $data['accounts'] = GetAccount::run();
        return view('pages.sempoa.configuration', $data);
    }

    public function store(SempoaConfigurationStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            CheckToken::run($request->token);
            $config = SempoaConfiguration::firstOrNew([
                'school_id' => session('school_id'),
            ]);
            $config->status = SempoaConfiguration::STATUS_OPEN;
            $config->token = $request->token;
            $config->save();
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
            $config = SempoaConfiguration::firstOrNew([
                'school_id' => session('school_id'),
            ]);
            $config->status = SempoaConfiguration::STATUS_LOCKED;
            $config->token = $request->token;
            $config->tuition_debit_account = self::checkAccount($request->tuition_debit_account);
            $config->tuition_credit_account = self::checkAccount($request->tuition_credit_account);
            $config->invoice_debit_account = self::checkAccount($request->invoice_debit_account);
            $config->invoice_credit_account = self::checkAccount($request->invoice_credit_account);
            $config->expense_debit_account = self::checkAccount($request->expense_debit_account);
            $config->expense_credit_account = self::checkAccount($request->expense_credit_account);
            $config->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('sempoa-configuration.index')->withInput()->withToastError("Ops! " . $th->getMessage());
        }

        return to_route('sempoa-configuration.index')->withInput()->withToastSuccess('Konfigurasi berhasil disimpan!');
    }

    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'status' => 'required|string|in:' . SempoaConfiguration:: STATUS_OPEN . ',' . SempoaConfiguration::STATUS_LOCKED . ',' . SempoaConfiguration::STATUS_RESET,
            ]);

            $config = SempoaConfiguration::firstOrNew([
                'school_id' => session('school_id'),
            ]);
            $config->status = $request->status;
            $config->save();

            if ($request->status == SempoaConfiguration::STATUS_RESET) {
                $config->forceDelete();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('sempoa-configuration.index')->withInput()->withToastError("Ops! " . $th->getMessage());
        }

        return to_route('sempoa-configuration.index')->withInput()->withToastSuccess('Konfigurasi hak akses berhasil disimpan!');
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
