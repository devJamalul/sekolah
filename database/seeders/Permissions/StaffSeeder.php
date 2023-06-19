<?php

namespace Database\Seeders\Permissions;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // role users
        $super_admin = Role::whereName(User::ROLE_SUPER_ADMIN)->first();
        $ops_admin = Role::whereName(User::ROLE_OPS_ADMIN)->first();
        $admin_yayasan = Role::whereName(User::ROLE_ADMIN_YAYASAN)->first();
        $admin_sekolah = Role::whereName(User::ROLE_ADMIN_SEKOLAH)->first();
        $bendahara = Role::whereName(User::ROLE_BENDAHARA)->first();
        $tata_usaha = Role::whereName(User::ROLE_TATA_USAHA)->first();
        $kepala_sekolah = Role::whereName(User::ROLE_KEPALA_SEKOLAH)->first();

        // staff
        $roles = [
            'index' => 'staff.index',
            'create' => 'staff.create',
            'store' => 'staff.store',
            'show' => 'staff.show',
            'edit' => 'staff.edit',
            'update' => 'staff.update',
            'destroy' => 'staff.destroy',
            // 'restore' => 'staff.restore',
            // 'import' => 'staff.import',
            // 'report' => 'staff.report',
        ];

        // index staff
        $permission = Permission::firstOrCreate([
            'name' => $roles['index'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_yayasan, $admin_sekolah, $bendahara, $tata_usaha, $kepala_sekolah]);

        // create staff
        $permission = Permission::firstOrCreate([
            'name' => $roles['create'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $tata_usaha]);

        // store staff
        $permission = Permission::firstOrCreate([
            'name' => $roles['store'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $tata_usaha]);

        // show staff
        $permission = Permission::firstOrCreate([
            'name' => $roles['show'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $tata_usaha, $kepala_sekolah, $bendahara]);

        // edit staff
        $permission = Permission::firstOrCreate([
            'name' => $roles['edit'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $tata_usaha]);

        // update staff
        $permission = Permission::firstOrCreate([
            'name' => $roles['update'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $tata_usaha]);

        // destroy staff
        $permission = Permission::firstOrCreate([
            'name' => $roles['destroy'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $tata_usaha]);
    }
}
