<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            // AcademicYearsSeeder::class,
            // StudentsSeeder::class,
            // TuitionTypesSeeder::class,
            // StudentTuitionsSeeder::class,
            PermissionsSeeder::class,
            ConfigSeeder::class,
            TestSeeder::class,
        ]);
    }
}
