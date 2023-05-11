<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Datatables\UsersDatatables;
use App\Http\Controllers\Datatables\SchoolsDatatables;
use App\Http\Controllers\Datatables\StudentDatatables;
use App\Http\Controllers\Datatables\TransactionDatatables;
use App\Http\Controllers\Datatables\ExpenseReportDatatables;
use App\Http\Controllers\Datatables\TransactionReportDatatables;
use App\Http\Controllers\Datatables\StudentTuitionMasterDatatables;
use App\Http\Controllers\Datatables\TuitionApprovalDatatables;
use App\Http\Controllers\Datatables\WalletLogDatatables;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('academy-years', [App\Http\Controllers\Datatables\AcademyYearDatatables::class, 'index'])->name('academy-year');

    Route::get('schools', SchoolsDatatables::class)->name('schools');
    Route::get('users', UsersDatatables::class)->name('users');

    Route::get('students', [StudentDatatables::class, 'index'])->name('students');
    Route::get('students/tuition-master', [StudentTuitionMasterDatatables::class, 'index'])->name('students.tuition-master');

    Route::get('academic-years', [App\Http\Controllers\Datatables\AcademyYearDatatables::class, 'index'])->name('academic-years');

    Route::get('master-configs', [App\Http\Controllers\Datatables\MasterConfigDatatables::class, 'index'])->name('master-configs');

    Route::get('grade', [App\Http\Controllers\Datatables\GradeDatatables::class, 'index'])->name('grade');

    Route::get('transactions', TransactionDatatables::class)->name('transactions');

    Route::get('classroom', [App\Http\Controllers\Datatables\ClassroomDatatables::class, 'index'])->name('classroom');

    Route::get('tuition-type', [App\Http\Controllers\Datatables\TuitionTypeDatatables::class, 'index'])->name('tuition-type');

    Route::get('tuition-approval', [TuitionApprovalDatatables::class, 'index'])->name('tuition-approval');

    Route::get('assign-classroom-student', App\Http\Controllers\Datatables\AssignClassroomStudentDatatables::class)->name('assign-classroom-student');

    Route::get('tuition', [App\Http\Controllers\Datatables\TuitionDatatables::class, 'index'])->name('tuition');

    Route::get('payment-type', App\Http\Controllers\Datatables\PaymentTypeDatatables::class)->name('payment-type');

    Route::get('assign-students', [App\Http\Controllers\Datatables\AssignClassroomStudentDatatables::class, 'students'])->name('assign-students');

    Route::get('assign-classroom-staff', App\Http\Controllers\Datatables\AssignClassroomStaffDatatables::class)->name('assign-classroom-staff');

    Route::get('assign-staffs', [App\Http\Controllers\Datatables\AssignClassroomStaffDatatables::class, 'staffs'])->name('assign-staffs');

    Route::get('expense', [App\Http\Controllers\Datatables\ExpenseDatatables::class, 'index'])->name('expense');

    Route::get('staff', [App\Http\Controllers\Datatables\StaffDatatables::class, 'index'])->name('staff');

    Route::get('report-student-tuitions', App\Http\Controllers\Datatables\ReportStudentTuitionsDatatables::class)->name('report-student-tuitions');
    Route::get('transaction-report', TransactionReportDatatables::class)->name('transaction-report');

    Route::get('wallet', [App\Http\Controllers\Datatables\WalletDatatables::class, 'index'])->name('wallet');
    Route::get('wallet/{wallet}/logs', WalletLogDatatables::class)->name('wallet.logs');

    Route::get('report-school-finances', App\Http\Controllers\Datatables\ReportSchoolFinancesDatatables::class)->name('report-school-finances');

    Route::get('expense-report', ExpenseReportDatatables::class)->name('expense-report');

    Route::get('invoices', App\Http\Controllers\Datatables\InvoiceDatatables::class)->name('invoices');
});
