<?php

namespace Database\Seeders\Permissions;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TuitionApprovalSeeder extends Seeder
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

        // tuition approvals
        $roles = [
            'index' => 'tuition-approval.index',
            'create' => 'tuition-approval.create',
            'store' => 'tuition-approval.store',
            'show' => 'tuition-approval.show',
            'edit' => 'tuition-approval.edit',
            'update' => 'tuition-approval.update',
            'destroy' => 'tuition-approval.destroy',
            'restore' => 'tuition-approval.restore',
            'report' => 'tuition-approval.report',
        ];

        // index tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['index'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_yayasan, $admin_sekolah, $kepala_sekolah, $tata_usaha, $bendahara]);

        // create tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['create'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_yayasan, $admin_sekolah, $kepala_sekolah, $tata_usaha, $bendahara]);

        // store tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['store'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // show tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['show'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_yayasan, $admin_sekolah, $kepala_sekolah, $tata_usaha, $bendahara]);

        // edit tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['edit'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // update tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['update'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // destroy tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['destroy'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // restore tuition approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['restore'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin]);
    }
}
