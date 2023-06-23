<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TestSeeder extends Seeder
{
  public function run(): void
  {
    // Create school: SMA Kanisius
    DB::table('schools')->insert([
      'school_name' => 'SMA Kanisius',
      'province' => 'DKI Jakarta',
      'city' => 'Jakarta Pusat',
      'postal_code' => '10360',
      'address' => 'Jl Menteng Raya No 64',
      'grade' => 'SMA',
      'email' => 'info@smakanisius.sempoa.idx',
      'phone' => '02111223344',
      'foundation_head_name' => 'Pater Heru',
      'foundation_head_tlpn' => '081211223344',
      'foundation_head_email' => 'heru@smakanisius.sempoa.idx',
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s")
    ]);

    $school = School::where('school_name', '=', 'SMA Kanisius')->first();

    session([
      'school_id' => $school->getKey()
    ]);

    // Create academic year: 2023-2024
    DB::table('academic_years')->insert([
      'school_id' => $school->id,
      'academic_year_name' => '2023-2024',
      'status_years' => AcademicYear::STATUS_REGISTRATION,
      'year_start' => '2023-07-01',
      'year_end' => '2024-06-30',
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s")
    ]);

    $year = AcademicYear::where('academic_year_name', '=', '2023-2024')->first();

    // Create grade: 10-12
    for ($i=10; $i<13; $i++) {
      DB::table('grades')->insert([
        'school_id' => $school->id,
        'grade_name' => $i,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
      ]);

      $grade = Grade::where('grade_name', '=', $i)->first();

      for ($j=1; $j<5; $j++) {
        $className = strval($i).'-'.strval($j);

        // Create class: A-D each grade
        DB::table('classrooms')->insert([
          'school_id' => $school->id,
          'academic_year_id' => $year->id,
          'grade_id' => $grade->id,
          'name' => $className,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        ]);

        $classroom = Classroom::where('name', '=', $className)->first();

        for ($k=0; $k<20; $k++) {
          // Create students: 20 each class
          $faker = Faker::create('id_ID');
          $gender = $faker->randomElement(['L', 'P']);
          $studentName = $faker->name($gender);

          DB::table('students')->insert([
            'school_id' => $school->id,
            'name' => $studentName,
            'email' => $faker->unique()->safeEmail(),
            'gender' => $gender,
            'address' => $faker->address(),
            'dob' => $faker->dateTimeBetween('-20 years', '-18 years'),
            'religion' => 'katolik',
            'phone_number' => $faker->randomNumber(9, true),
            'family_card_number' => $faker->randomNumber(9, true),
            'nik' => $faker->randomNumber(9, true),
            'nisn' => $faker->randomNumber(9, true),
            'nis' => $faker->randomNumber(9, true),

            'father_name' => $faker->name('male'),
            'father_address' => $faker->address(),
            'father_phone_number' => $faker->randomNumber(9, true),

            'mother_name' => $faker->name('female'),
            'mother_address' => $faker->address(),
            'mother_phone_number' => $faker->randomNumber(9, true),

            'guardian_name' => $faker->name(),
            'guardian_address' => $faker->address(),
            'guardian_phone_number' => $faker->randomNumber(9, true),

            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
          ]);

          $student = Student::where('name', '=', $studentName)->first();

          DB::table('classroom_student')->insert([
            'classroom_id' => $classroom->id,
            'student_id' => $student->id,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
          ]);
        }
      }
    }
  }
}
