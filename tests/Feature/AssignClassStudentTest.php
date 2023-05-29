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
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

dataset('staff_cannot_crud', [
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],

]);
/**
 * END DATASET
 */


it("forbid another  guest", function () {
    $this
        ->get(route('assign-classroom-student.index'))
        ->assertRedirect(route('login'));
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
    $academicYear = AcademicYear::factory()->create([
        'school_id'   => $school->id,
        'status_years' => AcademicYear::STATUS_STARTED
    ]);

    $classroom = Classroom::factory()->create([
        'academic_year_id' => $academicYear->id,
        'school_id' => $school->id
    ]);

    $student  = Student::factory()->create([
        'school_id' => $school->id
    ]);

    $data = [
        'academy_years' => $academicYear->id,
        'classroom_id' => $classroom->id,
        'id' => [$student->id]
    ];

    session(['school_id' => $school->id]);
    $this->actingAs($user)
        ->post(route('assign-classroom-student.store'), $data)
        ->assertRedirect(route('assign-classroom-student.index', ['academic_year' => $academicYear->id]));

    $this->assertDatabaseHas('classroom_student', [
        'classroom_id' => $data['classroom_id'],
        'student_id' => $student->id
    ]);
})->with('staff_can_crud')->todo();


it('can  Change assign  Student classroom', function (User $user) {
    $school = School::factory()->create();
    $student = Student::factory()->create([
        'school_id' => $school->id
    ]);
    $classroom = Classroom::factory()->create([
        'school_id' => $school->id
    ]);
    ClassroomStudent::create(
        ['classroom_id' => $classroom->id, 'student_id' => $student->id]
    );

    $classroomNew = Classroom::factory()->create([
        'school_id' => $school->id
    ]);

    $data = [
        'type' => 'Pindah Kelas',
        'academic_year' => $classroomNew->academic_year_id,
        'classroom_old' => $classroom->id,
        'classroom_id' => $classroomNew->id,
        'id' => [$student->id]
    ];

    session(['school_id' => $school->id]);

    $this->actingAs($user)
        ->delete(route('assign-classroom-student.destroy'), $data)
        ->assertRedirect(route('assign-classroom-student.index', ['academic_year' => $classroomNew->academic_year_id]));

    $this->assertDatabaseMissing('classroom_student', [
        'classroom_id' => $classroom->id,
        'student_id' => $student->id
    ]);
})->with('staff_can_crud');

it('can  Up assign  Student classroom', function (User $user) {
    $school = School::factory()->create();
    $student = Student::factory()->create([
        'school_id' => $school->id
    ]);
    $classroom = Classroom::factory()->create([
        'school_id' => $school->id
    ]);
    ClassroomStudent::create(
        ['classroom_id' => $classroom->id, 'student_id' => $student->id]
    );

    $classroomNew = Classroom::factory()->create([
        'school_id' => $school->id
    ]);

    $data = [
        'type' => 'Naik kelas',
        'academic_year' => $classroomNew->academic_year_id,
        'classroom_old' => $classroom->id,
        'classroom_id' => $classroomNew->id,
        'id' => [$student->id]
    ];

    session(['school_id' => $school->id]);

    $this->actingAs($user)
        ->delete(route('assign-classroom-student.destroy'), $data)
        ->assertRedirect(route('assign-classroom-student.index', ['academic_year' => $classroomNew->academic_year_id]));

    $this->assertDatabaseHas('classroom_student', [
        'classroom_id' => $classroomNew->id,
        'student_id' => $student->id
    ]);
})->with('staff_can_crud');



it('forbid store as page user', function ($user) {
    $this->actingAs($user)
        ->post(route('assign-classroom-student.store'))
        ->assertNotFound();
})->with('staff_cannot_crud')->todo();


it('forbid destroy as page user', function ($user) {
    $this->actingAs($user)
        ->delete(route('assign-classroom-student.destroy'))
        ->assertNotFound();
})->with('staff_cannot_crud')->todo();
