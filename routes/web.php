<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\User\UsersController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\School\SchoolsController;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\AcademyYearController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\Reports\StudentReport;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TuitionTypeController;
use App\Http\Controllers\ConfigSchoolController;
use App\Http\Controllers\ExpenseDetailController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\Wallet\WalletController;
use App\Http\Controllers\PublishTuitionController;
use App\Http\Controllers\School\SchoolSelectorController;
use App\Http\Controllers\ExpenseApprovalController;
use App\Http\Controllers\ExpenseOutgoingController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\TuitionApprovalController;
use App\Http\Controllers\Wallet\WalletLogController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Middleware\School\RequireChangePassword;
use App\Http\Controllers\Invoice\PayInvoiceController;
use App\Http\Controllers\Wallet\TopUpWalletController;
use App\Http\Controllers\Invoice\VoidInvoiceController;
use App\Http\Controllers\Profile\EditProfileController;
use App\Http\Controllers\AssignClassroomStaffController;
use App\Http\Controllers\Profile\EditPasswordController;
use App\Http\Controllers\ReportSchoolFinancesController;
use App\Http\Controllers\School\SchoolProfileController;
use App\Http\Controllers\StudentTuitionMasterController;
use App\Http\Controllers\Invoice\InvoiceDetailController;
use App\Http\Controllers\Invoice\InvoiceReportController;
use App\Http\Controllers\ReportStudentTuitionsController;
use App\Http\Controllers\AssignClassroomStudentController;
use App\Http\Controllers\Invoice\PublishInvoiceController;
use App\Http\Controllers\User\ChangeUserPasswordController;
use App\Http\Controllers\User\UserVerificationController;

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

Route::permanentRedirect('/', 'home');

Route::get('/home', function () {
    return view('pages.home');
})->name('home')->middleware(['auth']);

Route::as('user-verification.')->controller(UserVerificationController::class)->group(function () {
    Route::get('user/{email}/{token}/verify', 'index')->name('index');
    Route::post('user/{email}/{token}/verify', 'store')->name('store');
});

Route::middleware(['auth'])->group(function () {
    // Academy Year
    Route::resource("academy-year", AcademyYearController::class)->except(['show']);

    // Grade
    Route::resource("grade", GradeController::class)->except(['show']);

    // School
    Route::name('schools.')->prefix('school-profile')->controller(SchoolProfileController::class)->group(function () {
        Route::get('/', 'index')->name('profile-index');
        Route::put('/', 'update')->name('profile-update');
    });
    Route::resource('schools', SchoolsController::class);

    // Classroom
    Route::resource("classroom", ClassroomController::class)->except(['show']);

    // Approvals
    Route::resource('tuition-approval', TuitionApprovalController::class);
    Route::resource('expense-approval', ExpenseApprovalController::class);

    // Outgoing
    Route::resource('expense-outgoing', ExpenseOutgoingController::class);

    // Student
    Route::get('students/import', [StudentsController::class, 'importStudent'])->name('students.import');
    Route::post('students/import-excel', [StudentsController::class, 'importStudentByExcel'])->name('students.importStudentByExcel');
    Route::resource('students', StudentsController::class);
    Route::resource('students/{id}/tuition-master', StudentTuitionMasterController::class);

    // Tuition Type
    Route::resource("tuition-type", TuitionTypeController::class)->except(['show']);

    // School Selector
    Route::post('school_selector', SchoolSelectorController::class)->name('school_selector')->middleware('role:super admin|ops admin');

    // Assign Classroom student
    Route::get('get-classroom', [AssignClassroomStudentController::class, 'classroom'])->name('get-classroom');
    Route::get('assign-classroom-student', AssignClassroomStudentController::class)->name(('assign-classroom-student.index'));
    Route::post('assign-classroom-student', [AssignClassroomStudentController::class, 'store'])->name(('assign-classroom-student.store'));
    Route::delete('assign-classroom-student', [AssignClassroomStudentController::class, 'destroy'])->name(('assign-classroom-student.destroy'));

    // Transactions
    Route::resource("transactions", TransactionController::class);
    Route::resource("transaction-report", TransactionReportController::class)->only(['index', 'store']);

    // Users
    Route::resource("users", UsersController::class);
    Route::controller(ChangeUserPasswordController::class)->group(function () {
        Route::get('users/{user}/reset-password', 'edit')->name('reset-user-password.edit');
        Route::put('users/{user}/reset-password', 'update')->name('reset-user-password.update');
    });

    // Tuition
    Route::resource('tuition', TuitionController::class)->except(['show']);
    Route::resource('publish-tuition', PublishTuitionController::class)->except(['show']);

    // Payment Type
    Route::resource("payment-type", PaymentTypeController::class)->except(['show']);

    // Assign staff student
    Route::get('assign-classroom-staff', AssignClassroomStaffController::class)->name(('assign-classroom-staff.index'));
    Route::get('assign-classroom-staff/create', [AssignClassroomStaffController::class, 'create'])->name(('assign-classroom-staff.create'));
    Route::post('assign-classroom-staff', [AssignClassroomStaffController::class, 'store'])->name(('assign-classroom-staff.store'));
    Route::delete('assign-classroom-staff', [AssignClassroomStaffController::class, 'destroy'])->name(('assign-classroom-staff.destroy'));
    Route::get('get-classroom-staff', [AssignClassroomStaffController::class, 'classroomStaff'])->name('get-classroom-staff');

    // Expense
    Route::resource('expense', ExpenseController::class);
    Route::get('expense/{expense}/publish-expense', [ExpenseController::class, 'ExpensePublish'])->name('expense.publish');
    Route::get('expense/{expense}/show-detail', [ExpenseController::class, 'ShowDetail'])->name('expense.show-detail');
    // Route::resource('expense-detail', ExpenseDetailController::class)->except(['show']);
    Route::controller(ExpenseDetailController::class)->prefix('expense')->name('expense-detail.')->group(function () {
        Route::get('/{expense}/detail', 'index')->name('index');
        Route::post('/{expense}/detail', 'store')->name('store');
        Route::delete('/{expense}/detail/{expense_detail}', 'destroy')->name('destroy');
    });
    Route::resource("expense-report", ExpenseReportController::class)->only(['index', 'store']);

    //staff
    Route::resource("staff", StaffController::class)->except(['show']);

    // Report Student Tuitions
    Route::get('report-student-tuition', [ReportStudentTuitionsController::class, 'index'])->name('report-student-tuition');
    Route::post('export-student-tuition', [ReportStudentTuitionsController::class, 'export'])->name('export-student-tuition');

    //staff
    Route::resource("staff", StaffController::class);

    // Wallet
    Route::resource("wallet", WalletController::class)->except(['show']);
    Route::get('wallet/{wallet}/logs', WalletLogController::class)->name('wallet.logs');
    Route::controller(TopUpWalletController::class)->prefix('wallet')->name('wallet.')->group(function () {
        Route::get('{wallet}/topup', 'show')->name('topup.show');
        Route::post('{wallet}/topup', 'store')->name('topup.store');
    });

    // report school finances
    Route::get('report-school-finances', [ReportSchoolFinancesController::class, 'index'])->name('report-school-finances.index');
    Route::post('report-school-finances', [ReportSchoolFinancesController::class, 'report'])->name('report-school-finances.show');
    Route::get('export-report-school-finances', [ReportSchoolFinancesController::class, 'export'])->name('report-school-finances.export');

    // Invoice
    Route::resource('invoices', InvoiceController::class)->except('show');
    Route::get('invoices/{invoice}/publish', PublishInvoiceController::class)->name('invoices.publish');
    Route::get('invoices/{invoice}/pay', [PayInvoiceController::class, 'index'])->name('invoices.pay');
    Route::post('invoices/{invoice}/pay', [PayInvoiceController::class, 'store'])->name('invoices.payment')->middleware('password.confirm');
    Route::get('invoices/{invoice}/void', [VoidInvoiceController::class, 'index'])->name('invoices.void');
    Route::post('invoices/{invoice}/void', [VoidInvoiceController::class, 'store'])->name('invoices.voidment')->middleware('password.confirm');
    Route::controller(InvoiceDetailController::class)->prefix('invoices')->name('invoice-details.')->group(function () {
        Route::get('/{invoice}/detail', 'index')->name('index');
        Route::post('/{invoice}/detail', 'store')->name('store');
        Route::delete('/{invoice}/detail/{invoice_detail}', 'destroy')->name('destroy');
    });
    Route::get('reports/invoices', [InvoiceReportController::class, 'index'])->name('invoices.report');
    Route::post('reports/invoices', [InvoiceReportController::class, 'store'])->name('invoices.report-result');

    // Profile dan Password
    Route::apiSingleton('edit-profile', EditProfileController::class);
    Route::apiSingleton('edit-password', EditPasswordController::class)->withoutMiddleware([RequireChangePassword::class]);
});

Route::middleware(['auth'])->prefix('reports')->group(function () {

    // Report Student
    Route::get('students', [StudentReport::class, 'index'])->name('reports.students');
    Route::post('students/get-classroom', [StudentReport::class, 'getClassroomByFilter'])->name('reports.students.getClassroomByFilter');
    Route::post('students', [StudentReport::class, 'exportStudentReport'])->name('reports.students.export');
    Route::post('expense', [ExpenseReportController::class, 'exportExpenseReport'])->name('reports.expense.export');
});


Route::group(['middleware' => ['auth']], function () {
    Route::resource("master-configs", ConfigController::class)->except(['show']);
});

Route::group(['prefix' => 'config', 'as' => 'config.', 'middleware' => ['auth']], function () {
    Route::get('/', [ConfigSchoolController::class, 'index'])->name('index');
    Route::post('/save', [ConfigSchoolController::class, 'save'])->name('save');
});

Route::fallback(function () {
    abort(404);
});
