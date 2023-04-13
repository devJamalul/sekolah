<?php

use App\Models\User;
use App\Models\School;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\ClassroomStudent;
use App\Models\Grade;

beforeEach(function () {
    $this->superAdmin = User::role(User::ROLE_SUPER_ADMIN)->first();
    $this->opsAdmin = User::role(User::ROLE_OPS_ADMIN)->first();
    $this->adminYayasan = User::role(User::ROLE_ADMIN_YAYASAN)->first();
    $this->adminSekolah = User::role(User::ROLE_ADMIN_SEKOLAH)->first();
    $this->bendahara = User::role(User::ROLE_BENDAHARA)->first();
    $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->first();
    $this->kepalaSekolah = User::role(User::ROLE_KEPALA_SEKOLAH)->first();
    $this->setupFaker();
});

/**
 * DATASET
 */
dataset('staff_can_crud', [
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha]
]);

dataset('staff_only_read', [
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

dataset('staff_cannot_crud', [
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],

]);
/**
 * END DATASET
 */


it("forbid another  guest", function () {
    $this
        ->get(route('assign-classroom-student.index'))
        ->assertNotFound();
});


it('can render page assign class student', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->id]);
    $this->actingAs($user)->get(route('assign-classroom-student.index'))->assertOk();
})->with('staff_can_crud');



it('required add id classroom', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->id]);
    $classroom = Classroom::factory();
    $student = Student::factory()->create();
    $data = [
        'id' => [
            $student->id
        ],
        'classroom_id' => '',
    ];
    $this->actingAs($user)->post(route('assign-classroom-student.store'), $data)->assertInvalid(['classroom_id']);
})->with('staff_can_crud');


it('required  add id students', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->id]);
    $academicYear = AcademicYear::factory()->create([
        'status_years' => AcademicYear::STATUS_STARTED
    ]);
    session([
        'academic_year_id' => $academicYear->id
    ]);


    $classroom = Classroom::factory()->create();
    $student = Student::factory()->create();

    $data = [
        'id' => '',
        'classroom_id' => $classroom->id,
    ];

    $this->actingAs($user)->post(route('assign-classroom-student.store'), $data)->assertInvalid(['id']);
})->with('staff_can_crud');


it("can store student classroom", function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->id]);
    $academicYear = AcademicYear::factory()->create([
        'status_years' => AcademicYear::STATUS_STARTED
    ]);
    session([
        'academic_year_id' => $academicYear->id
    ]);



    $classroom = Classroom::factory()->has(Student::factory(1))->create();
    $student  = $classroom->students()->first();
    $data = [
        'classroom_id' => $student->pivot->classroom_id,
        'id' => [$student->id]
    ];

    $this->actingAs($user)
        ->post(route('assign-classroom-student.store'), $data)
        ->assertRedirect(route('assign-classroom-student.index'));

    $this->assertDatabaseHas('classroom_student', [
        'classroom_id' => $student->pivot->classroom_id,
        'student_id' => $student->id
    ]);
})->with('staff_can_crud');


it('can  Destroy  Student classroom', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->id]);
    $classroom = Classroom::factory()->has(Student::factory(1))->create([
        'school_id' => $school->id
    ]);
    $student  = $classroom->students()->first();
    $data = [
        'classroom_id' => $student->pivot->classroom_id,
        'id' => [$student->id]
    ];

    $this->actingAs($user)
        ->delete(route('assign-classroom-student.destroy'), $data)
        ->assertRedirect(route('assign-classroom-student.index'));

    $this->assertDatabaseMissing('classroom_student', $data);
})->with('staff_can_crud');


it('forbid store as page user', function ($user) {
    $this->actingAs($user)
        ->post(route('assign-classroom-student.store'))
        ->assertNotFound();
})->with('staff_cannot_crud');


it('forbid destroy as page user', function ($user) {
    $this->actingAs($user)
        ->delete(route('assign-classroom-student.destroy'))
        ->assertNotFound();
})->with('staff_cannot_crud');
