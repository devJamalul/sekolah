<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SchoolsController;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\AcademyYearController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TuitionTypeController;
use App\Http\Controllers\ConfigSchoolController;
use App\Http\Controllers\ExpenseDetailController;
use App\Http\Controllers\PublishTuitionController;
use App\Http\Controllers\SchoolSelectorController;
use App\Http\Controllers\AssignClassroomStaffController;
use App\Http\Controllers\AssignClassroomStudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('pages.home');
})->name('home')->middleware(['auth']);

Route::group([], function () {
    // Academy Year
    Route::resource("academy-year", AcademyYearController::class)->except(['show']);

    // Grade
    Route::resource("grade", GradeController::class)->except(['show']);

    // School
    Route::resource('schools', SchoolsController::class)->except('show');
    // Classroom
    Route::resource("classroom", ClassroomController::class)->except(['show']);

    // Student
    Route::resource('students', StudentsController::class)->except(['show']);
    Route::get('students/tuition-master', [StudentsController::class, 'tuitionMaster'])->name('students.tuition-master');
    Route::get('students/import', [StudentsController::class, 'importStudent'])->name('students.import');
    Route::post('students/import-excel', [StudentsController::class, 'importStudentByExcel'])->name('students.importStudentByExcel');

    // Tuition Type
    Route::resource("tuition-type", TuitionTypeController::class)->except(['show']);

    // School Selector
    Route::post('school_selector', SchoolSelectorController::class)->name('school_selector')->middleware('role:super admin|ops admin');

    // Assign Classroom student
    Route::get('assign-classroom-student', AssignClassroomStudentController::class)->name(('assign-classroom-student.index'));
    Route::post('assign-classroom-student', [AssignClassroomStudentController::class, 'store'])->name(('assign-classroom-student.store'));
    Route::delete('assign-classroom-student', [AssignClassroomStudentController::class, 'destroy'])->name(('assign-classroom-student.destroy'));


    // Transactions
    Route::resource("transactions", TransactionController::class);

    // Users
    Route::resource("users", UsersController::class);
    // Tuition
    Route::resource('tuition', TuitionController::class)->except(['show']);
    Route::resource('publish-tuition', PublishTuitionController::class)->except(['show']);

    // Payment Type
    Route::resource("payment-type", PaymentTypeController::class)->exce
    
    // Assign staff student
    Route::get('assign-classroom-staff', AssignClassroomStaffController::class)->name(('assign-classroom-staff.index'));
    Route::post('assign-classroom-staff', [AssignClassroomStaffController::class, 'store'])->name(('assign-classroom-staff.store'));
    Route::delete('assign-classroom-staff', [AssignClassroomStaffController::class, 'destroy'])->name(('assign-classroom-staff.destroy'));
    
    // Expense
    Route::resource('expense', ExpenseController::class);
    Route::resource('expense-detail', ExpenseDetailController::class)->except(['show']);

    //staff
    Route::resource("staff", StaffController::class)->except(['show']);
});

Route::group([], function () {
    Route::resource("master-configs", ConfigController::class)->except(['show']);
});

Route::group(['prefix' => 'config', 'as' => 'config.'], function () {
    Route::get('/', [ConfigSchoolController::class, 'index'])->name('index');
    Route::post('/save', [ConfigSchoolController::class, 'save'])->name('save');
});

Route::fallback(function () {
    abort(404);
});
