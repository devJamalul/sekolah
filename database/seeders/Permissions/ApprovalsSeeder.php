<?php

namespace Database\Seeders\Permissions;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApprovalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // role users
        $super_admin = Role::whereName(User::ROLE_SUPER_ADMIN)->first();
        $ops_admin = Role::whereName(User::ROLE_OPS_ADMIN)->first();
        $kepala_sekolah = Role::whereName(User::ROLE_KEPALA_SEKOLAH)->first();

        // approvals
        $roles = [
            'index' => 'approvals.index',
            'create' => 'approvals.create',
            'store' => 'approvals.store',
            'show' => 'approvals.show',
            'edit' => 'approvals.edit',
            'update' => 'approvals.update',
            'destroy' => 'approvals.destroy',
            'restore' => 'approvals.restore',
            'report' => 'approvals.report',
        ];

        // index approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['index'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // create approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['create'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // store approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['store'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // show approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['show'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // edit approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['edit'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // update approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['update'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // destroy approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['destroy'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $kepala_sekolah]);

        // restore approvals
        $permission = Permission::firstOrCreate([
            'name' => $roles['restore'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin]);
    }
}
