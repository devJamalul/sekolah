<?php

namespace App\Http\Controllers;

use App\Actions\Sempoa\CheckToken;
use Illuminate\Http\Request;
use App\Models\Config;
use App\Models\ConfigSchool;
use App\Http\Requests\SchoolConfigRequest;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ConfigSchoolController extends Controller
{
    public function index()
    {
        $data['title'] = "Konfigurasi";
        $data['configs'] = Config::active()->get();
        // $data = Config::where('config_schools.school_id', session('school_id'))->leftJoin("config_schools", "configs.code", "=", "code_config")->get();
        return view('pages.config-school.list', $data);
    }

    public function save(SchoolConfigRequest $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->config as $key => $val) {
                $save = ConfigSchool::firstOrNew([
                    'school_id' => session('school_id'),
                    'config_id' => $val
                ]);
                $save->value = $request->value[$key];
                $save->save();
            }

            DB::commit();
            Alert::toast('Save Config Success ', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::toast('Ops Error Save Config. ' . $th->getMessage(), 'error');
        }

        return redirect()->route('config.index');
    }
}
