<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Permissions\UserSeeder;
use Database\Seeders\Permissions\GradeSeeder;
use Database\Seeders\Permissions\StaffSeeder;
use Database\Seeders\Permissions\SchoolSeeder;
use Database\Seeders\Permissions\WalletSeeder;
use Database\Seeders\Permissions\ExpenseSeeder;
use Database\Seeders\Permissions\InvoiceSeeder;
use Database\Seeders\Permissions\StudentSeeder;
use Database\Seeders\Permissions\TuitionSeeder;
use Database\Seeders\Permissions\ClassroomSeeder;
use Database\Seeders\Permissions\PaymentTypeSeeder;
use Database\Seeders\Permissions\TransactionSeeder;
use Database\Seeders\Permissions\TuitionTypeSeeder;
use Database\Seeders\Permissions\AcademicYearSeeder;
use Database\Seeders\Permissions\MasterConfigSeeder;
use Database\Seeders\Permissions\SchoolConfigSeeder;
use Database\Seeders\Permissions\ExpenseDetailSeeder;
use Database\Seeders\Permissions\ExpenseReportSeeder;
use Database\Seeders\Permissions\InvoiceDetailSeeder;
use Database\Seeders\Permissions\SchoolSelectorSeeder;
use Database\Seeders\Permissions\TuitionApprovalSeeder;
use Database\Seeders\Permissions\TransactionReportSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Permissions\AssignClassroomStaffSeeder;
use Database\Seeders\Permissions\ReportSchoolFinancesSeeder;
use Database\Seeders\Permissions\StudentTuitionMasterSeeder;
use Database\Seeders\Permissions\AssignClassroomStudentSeeder;
use Database\Seeders\Permissions\ExpenseApprovalSeeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /***
        // role users documentation
        $super_admin = Role::whereName(User::ROLE_SUPER_ADMIN)->first();
        $ops_admin = Role::whereName(User::ROLE_OPS_ADMIN)->first();
        $admin_sekolah = Role::whereName(User::ROLE_ADMIN_SEKOLAH)->first();
        $admin_yayasan = Role::whereName(User::ROLE_ADMIN_YAYASAN)->first();
        $tata_usaha = Role::whereName(User::ROLE_TATA_USAHA)->first();
        $bendahara = Role::whereName(User::ROLE_BENDAHARA)->first();
        $kepala_sekolah = Role::whereName(User::ROLE_KEPALA_SEKOLAH)->first();
         */

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call([
            SchoolSeeder::class,
            StudentSeeder::class,
            GradeSeeder::class,
            TuitionTypeSeeder::class,
            AcademicYearSeeder::class,
            ClassroomSeeder::class,
            SchoolSelectorSeeder::class,
            MasterConfigSeeder::class,
            SchoolConfigSeeder::class,
            TransactionSeeder::class,
            UserSeeder::class,
            AssignClassroomStudentSeeder::class,
            TuitionSeeder::class,
            PaymentTypeSeeder::class,
            AssignClassroomStaffSeeder::class,
            StudentTuitionMasterSeeder::class,
            AssignClassroomStaffSeeder::class,
            TransactionReportSeeder::class,
            WalletSeeder::class,
            InvoiceSeeder::class,
            InvoiceDetailSeeder::class,
            ExpenseSeeder::class,
            ExpenseDetailSeeder::class,
            ExpenseReportSeeder::class,
            TuitionApprovalSeeder::class,
            ReportSchoolFinancesSeeder::class,
            StaffSeeder::class,
            ExpenseApprovalSeeder::class
        ]);
    }
}
