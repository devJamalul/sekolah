<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class AdminSekolahController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return view('pages.home.admin-sekolah');
    }
}