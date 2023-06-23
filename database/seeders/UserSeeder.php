<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $role = User::ROLE_SUPER_ADMIN;
        $user = User::updateOrCreate(
            [
                'email' => 'admin@sempoa.id',
            ],
            [
                'name' => str($role)->title(),
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]
        );
        $user->assignRole($role);

        // local env
        if (true) {
            // Ops Admin
            $role = User::ROLE_OPS_ADMIN;
            $user = User::updateOrCreate(
                [
                    'email' => 'ops@sempoa.id',
                ],
                [
                    'name' => str($role)->title(),
                    'password' => bcrypt('password'),
                    'email_verified_at' => now()
                ]
            );
            $user->assignRole($role);

            // Sekolah
            // $sekolah = School::updateOrCreate([
            //     'school_name' => 'Sekolah SD Karmel',
            // ]);
            // $roles = [
            //     User::ROLE_ADMIN_SEKOLAH,
            //     User::ROLE_TATA_USAHA,
            //     User::ROLE_BENDAHARA,
            //     User::ROLE_KEPALA_SEKOLAH,
            // ];

            // foreach ($roles as $role) {
            //     $user = User::updateOrCreate(
            //         [
            //             'email' => str($role)->slug() . '@sekolah.com',
            //         ],
            //         [
            //             'name' => str($role)->title(),
            //             'password' => bcrypt('password'),
            //             'email_verified_at' => now(),
            //             'school_id' => $sekolah->getKey()
            //         ]
            //     );
            //     $user->assignRole($role);
            //     $user->staff()->updateOrCreate([
            //         'school_id' => $sekolah->getKey(),
            //     ]);
            // }

            // $sekolah->staff_id = 1;
            // $sekolah->save();
        }
    }
}
