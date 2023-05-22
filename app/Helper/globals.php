<?php

use App\Models\ConfigSchool;


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

function formatAngka(string|array|null $nominal): int|array
{
    if (is_array($nominal)) {
        $data = [];
        foreach ($nominal as $item) {
            $data[] = (int) str_replace(".", "", $item);
        }
        return $data;
    }

    if (is_null($nominal)) $nominal = 0;
    return (int) str_replace(".", "", $nominal);
}
