<?php

use App\Models\User;
use App\Models\Grade;
use App\Models\School;
use App\Models\Classroom;
use App\Models\AcademicYear;


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

// Super Admin & Ops Admin Role
it('has Super Admin & Ops Admin role', function () {
    $this
        ->assertDatabaseHas('roles', [
            'name' => User::ROLE_SUPER_ADMIN
        ])
        ->assertDatabaseHas('roles', [
            'name' => User::ROLE_OPS_ADMIN
        ]);
});


it('has Super Admin & Ops Admin users', function () {
    $this
        ->assertDatabaseHas('users', [
            'name' => str(User::ROLE_SUPER_ADMIN)->title()
        ])
        ->assertDatabaseHas('users', [
            'name' => str(User::ROLE_OPS_ADMIN)->title()
        ]);
});

// Forbid Guest
it('forbid guest to view Classroom page', function () {
    $this
        ->get(route('classroom.index'))
        ->assertRedirect('login');
});

// Render Create
it('can render Classroom create page', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('classroom.create'));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

it('can create new Classroom', function (User $user) {
    $school = School::factory()->create();
    $academicYear = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();
    $name = $this->faker()->word();

    $this->actingAs($user)
        ->post(route('classroom.store'), [
            'school_id' => $school->getKey(),
            'academic_year_id' => $academicYear->getKey(),
            'grade_id' => $grade->getKey(),
            'name' => $name
        ])
        ->assertRedirect(route('classroom.index'));

    $this->assertDatabaseHas('classrooms', [
        'name' => $name
    ]);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

// Render Index
it('can render Classroom index page', function (User $user) {
    $response = $this->actingAs($user)
        ->get(route('classroom.index'));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

// Render Update
it('can render Classroom edit page', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $name = $this->faker()->word();

    $classroom = $school->classrooms()->create([
        'school_id' => $school->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'name' => $name
    ]);

    $this->assertDatabaseHas('classrooms', [
        'id' => $classroom->getKey(),
        'school_id' => $school->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'name' => $name
    ]);

    $response = $this->actingAs($user)
        ->get(route('classroom.edit', $classroom->getKey()));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

it('can edit classroom', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();
    $name = $this->faker()->word();
    $classroom = $school->classrooms()->create([
        'school_id' => $school->id,
        'academic_year_id' => $academicYear->id,
        'grade_id' => $grade->id,
        'name' => $name
    ]);
    $this->actingAs($user)
        ->put(route('classroom.update', $classroom->getKey()), [
            'school_id' => $classroom->school_id,
            'academic_year_id' => $classroom->academic_year_id,
            'grade_id' => $classroom->grade_id,
            'name' => $classroom->name,
        ])
        ->assertRedirect(route('classroom.index'));
    $this->assertDatabaseHas('classrooms', [
        'name' => $classroom->name
    ]);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);


// Render Delete
it('can delete Classroom', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();
    $name = $this->faker()->word();
    $classroom = $school->classrooms()->create([
        'school_id' => $school->id,
        'academic_year_id' => $academicYear->id,
        'grade_id' => $grade->id,
        'name' => $name
    ]);
    $this->actingAs($user)
        ->delete(route('classroom.destroy', $classroom->getKey()))
        ->assertStatus(200);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);


// Negation CRUD
it('can not render Classroom create page', function (User $user) {
    $response = $this->actingAs($user)
        ->get(route('classroom.create'));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

it('can not create new Classroom with Invalid requires', function (User $user) {
    $this->actingAs($user)
        ->post(route('classroom.store'))
        ->assertSessionHasErrors(['academic_year_id', 'grade_id', 'name']);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

it('can not render Classroom edit page', function (User $user) {
    $school = School::factory()->create();
    $academicYear = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();
    $name = $this->faker()->word();
    $classroom = $school->classrooms()->create([
        'school_id' => $school->id,
        'academic_year_id' => $academicYear->id,
        'grade_id' => $grade->id,
        'name' => $name
    ]);

    $response = $this->actingAs($user)
        ->get(route('classroom.edit', $classroom->getKey()));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

it('can not edit Classroom with Invalid requires', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();
    $name = $this->faker()->word();
    $classroom = $school->classrooms()->create([
        'school_id' => $school->id,
        'academic_year_id' => $academicYear->id,
        'grade_id' => $grade->id,
        'name' => $name
    ]);

    $this->actingAs($user)
        ->put(route('classroom.update', $classroom->getKey()), [
            'school_id' => '',
            'academic_year_id' => '',
            'grade_id' => '',
            'name' => ''
        ])
        ->assertSessionHasErrors(['academic_year_id', 'grade_id', 'name']);;
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

it('can not delete Classroom', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $classroom = $school->classrooms()->create();

    $response = $this->actingAs($user)
        ->delete(route('classroom.destroy', $classroom->getKey()));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);
