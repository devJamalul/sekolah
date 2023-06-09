<?php

namespace Database\Seeders\Permissions;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ExpenseOutgoingSeeder extends Seeder
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

        // expense approvals
        $roles = [
            'index' => 'expense-outgoing.index',
            'create' => 'expense-outgoing.create',
            'store' => 'expense-outgoing.store',
            'show' => 'expense-outgoing.show',
            'edit' => 'expense-outgoing.edit',
            'update' => 'expense-outgoing.update',
            'destroy' => 'expense-outgoing.destroy',
            'restore' => 'expense-outgoing.restore',
            'report' => 'expense-outgoing.report',
        ];

        // index expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['index'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_yayasan, $admin_sekolah, $kepala_sekolah, $tata_usaha, $bendahara]);

        // create expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['create'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_yayasan, $admin_sekolah, $kepala_sekolah, $tata_usaha, $bendahara]);

        // store expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['store'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);

        // show expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['show'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_yayasan, $admin_sekolah, $kepala_sekolah, $tata_usaha, $bendahara]);

        // edit expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['edit'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);

        // update expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['update'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);

        // destroy expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['destroy'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);

        // restore expense approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['restore'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin]);
    }
}
