<?php

namespace App\Http\Controllers\Home;

use App\Models\Grade;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Staff;

class AdminSekolahController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {

        $data['total_students'] = 'Total Students';
        $data['total_students_class'] = 'dark';
        $data['grades'] = Grade::get();
        $data['classrooms'] = Classroom::with('academic_year')->get();
        $data['classroomStudent'] = ClassroomStudent::get();


        $data['total_staff'] = 'Total Staff';
        $data['total_staff_class'] = 'dark';
        $data['staff'] = Staff::get()->count();
        $data['staff_icon'] = 'fas fa-users';


        return view('pages.home.admin-sekolah', $data);
    }
}
