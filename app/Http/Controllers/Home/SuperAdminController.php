<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;

class SuperAdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $data['title'] = 'Sempoa Dashboard';
        $data['schools'] = School::with(['academic_years', 'classrooms', 'grades', 'payment_types', 'staff', 'students', 'tuitions', 'tuition_types', 'users', 'wallets'])->get();
        return view('pages.home.super-admin', $data);
    }
}
