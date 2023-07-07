<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Grade;

class KepalaSekolahController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $data['grades'] = Grade::query()
            ->with(['classrooms.students'])
            ->get();
        return view('pages.home.kepala-sekolah', $data);
    }
}
