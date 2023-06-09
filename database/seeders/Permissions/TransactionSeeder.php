<?php

namespace Database\Seeders\Permissions;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TransactionSeeder extends Seeder
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

        // transactions
        $roles = [
            'index' => 'transactions.index',
            'create' => 'transactions.create',
            'store' => 'transactions.store',
            'edit' => 'transactions.edit',
            'update' => 'transactions.update',
            'destroy' => 'transactions.destroy'
        ];

        // index
        $permission = Permission::firstOrCreate([
            'name' => $roles['index'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);

        // create
        $permission = Permission::firstOrCreate([
            'name' => $roles['create'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);
        $permission = Permission::firstOrCreate([
            'name' => $roles['store'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);

        // update
        $permission = Permission::firstOrCreate([
            'name' => $roles['edit'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);
        $permission = Permission::firstOrCreate([
            'name' => $roles['update'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);

        // destroy
        $permission = Permission::firstOrCreate([
            'name' => $roles['destroy'],
            'guard_name' => 'web'
        ]);
        $permission->syncRoles([$super_admin, $ops_admin, $admin_sekolah, $bendahara]);
    }
}
