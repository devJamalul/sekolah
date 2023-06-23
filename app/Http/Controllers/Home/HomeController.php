<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $role = $request->user()->getRoleNames()[0];
        return match ($role) {
            User::ROLE_SUPER_ADMIN => to_route('home.super-admin'),
            User::ROLE_OPS_ADMIN => to_route('home.ops-sekolah'),
            User::ROLE_ADMIN_SEKOLAH => to_route('home.admin-sekolah'),
            User::ROLE_KEPALA_SEKOLAH => to_route('home.kepala-sekolah'),
            User::ROLE_TATA_USAHA => to_route('home.tata-usaha'),
            User::ROLE_BENDAHARA => to_route('home.bendahara'),
            default => view('pages.home')
        };
    }
}
