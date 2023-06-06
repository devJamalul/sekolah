<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\ConfigRequest;
use App\Models\Config;
use DB;

class ConfigController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = "Master Konfigurasi";
        return view('pages.config.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Create Master Configuration";
        return view('pages.config.create', compact ('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConfigRequest $request)
    {

        DB::beginTransaction();
        try {

            $config            = new config();
            $config->code = $request->code;
            $config->name      = $request->name;
            $config->save();

            DB::commit();
            Alert::toast('Save Master Config Success ', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::toast('Ops Error Save Master Config', 'error');
        }

        return redirect()->route('master-configs.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Config $master_config)
    {

        $title = "Edit Master Configuration";
        return view('pages.config.edit', compact('master_config', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConfigRequest $request, Config $master_config)
    {

        DB::beginTransaction();
        try {

            $master_config->name      = $request->name;
            $master_config->save();

            DB::commit();
            Alert::toast('Save Master Config Success ', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::toast('Ops Error Save Master Config', 'error');
        }

        return redirect()->route('master-configs.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Config $master_config)
    {

        DB::beginTransaction();
        try {

            $code = $master_config->code;
            $master_config->delete();

            Alert::toast('Delete Config with code '.$code.' Success ', 'success');
            DB::commit();
        } catch (\Throwable $th) {
            Alert::toast('Ops Error Delete Master Config', 'error');
            DB::rollback();
        }

        return redirect()->route('master-configs.index');
    }
}
