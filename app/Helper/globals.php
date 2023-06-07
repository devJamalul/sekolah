<?php

use App\Models\ConfigSchool;
use Illuminate\Database\Eloquent\Builder;

function getConfigBySchool($code,$school_id){
    $data = ConfigSchool::where([['code_config',$code],["school_id",$school_id]])->first();
    if($data){

        $result = [
            'status' => 200,
            'data' =>$data
        ];

    }else{
        $result = [
            'status' => 404,
            'data' => "data not found"
        ];
    }
    return json_encode($result);
}

/**
 * contoh: schoolConfig('sempoa') atau schoolConfig(config: 'sempoa', school_id: 1)
 */
function schoolConfig(int|string $config, int $school_id = null)
{
    $query = ConfigSchool::where([
        "school_id" => $school_id ?? session('school_id')
    ])
    ->when(is_int($config), function (Builder $query, int $config) {
        $query->where('config_id', $config);
    })
    ->when(is_string($config), function (Builder $query) use ($config) {
        $query->whereHas('config', function ($query) use ($config) {
            $query->where('code', $config);
        });
    });

    $data = $query->first();

    return $data->value ?? '';
}

/**
 * Menghapus "titik" pada value, misalnya 1.234 menjadi 1234
 */
function formatAngka(string|int|array|null $nominal): mixed
{
    if (is_array($nominal)) {
        $data = [];
        foreach ($nominal as $item) {
            $data[] = (int) strtr($item, ".,", "");
        }
        return $data;
    }

    if (is_null($nominal)) $nominal = 0;
    return str_replace(".", "", $nominal);
}
