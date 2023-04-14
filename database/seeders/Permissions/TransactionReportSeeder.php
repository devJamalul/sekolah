<?php

namespace Database\Seeders\Permissions;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TransactionReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin = Role::whereName(User::ROLE_SUPER_ADMIN)->first();
        $ops_admin = Role::whereName(User::ROLE_OPS_ADMIN)->first();
        $admin_sekolah = Role::whereName(User::ROLE_ADMIN_SEKOLAH)->first();
        $admin_yayasan = Role::whereName(User::ROLE_ADMIN_YAYASAN)->first();
        $tata_usaha = Role::whereName(User::ROLE_TATA_USAHA)->first();
        $bendahara = Role::whereName(User::ROLE_BENDAHARA)->first();
        $kepala_sekolah = Role::whereName(User::ROLE_KEPALA_SEKOLAH)->first();

        $resource = "transaction-report";

        $roles = [
            'index' => $resource . '.index',
            'store' => $resource . '.store',
            'show' => $resource . '.show',
        ];

        // index
        $permission = Permission::firstOrCreate([
            'name' => $roles['index'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $admin_yayasan, $kepala_sekolah, $tata_usaha, $bendahara]);

        // create
        $permission = Permission::firstOrCreate([
            'name' => $roles['store'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $admin_yayasan, $kepala_sekolah, $tata_usaha, $bendahara]);

        // show
        $permission = Permission::firstOrCreate([
            'name' => $roles['show'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $admin_yayasan, $kepala_sekolah, $tata_usaha, $bendahara]);
    }
}
