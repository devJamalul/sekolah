<?php

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\User;

beforeEach(function () {
    session(['school_id' => 1]);

    $this->superAdmin = User::role(User::ROLE_SUPER_ADMIN)->first();
    $this->opsAdmin = User::role(User::ROLE_OPS_ADMIN)->first();

    $this->adminSekolah = User::role(User::ROLE_ADMIN_SEKOLAH)->firstWhere([
        'school_id' => session('school_id')
    ]);

    $this->bendahara = User::role(User::ROLE_BENDAHARA)->firstWhere([
        'school_id' => session('school_id')
    ]);

    $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->firstWhere([
        'school_id' => session('school_id')
    ]);

    $this->kepalaSekolah = User::role(User::ROLE_KEPALA_SEKOLAH)->firstWhere([
        'school_id' => session('school_id')
    ]);

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


/**
 * OUTSIDE CRUD
 */
it('forbid guest to view Academy Years page', function () {
    $this
        ->get(route('academy-year.index'))
        ->assertRedirectToRoute('login');
});


/**
 * CREATE RENDER PAGE
 */
it('can render Academy Years create page as ', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('academy-year.create'));

    $response->assertOk();
})->with('staff_can_crud');



/**
 * CREATE VALIDATION
 */

it('can render Academy Years create invalid required school_id and name as ', function (User $user) {
    $this->actingAs($user)
        ->post(route('academy-year.store'), [
            'school_id' => '',
            'academic_year_name' => '',
        ])
        ->assertInvalid([
            'school_id',
            'academic_year_name'
        ]);
})->with('staff_can_crud');

it('can render Academy Years create invalid academy years formatted  as ', function (User $user) {
    $school = School::factory()->create();
    $this->actingAs($user)
        ->post(route('academy-year.store'), [
            'school_id' => $school->id,
            'academic_year_name' => fake()->name(),
        ])->assertInvalid(['academic_year_name']);
})->with('staff_can_crud');


/**
 * CREATE
 */
it('can render Academy Years create data post  as ', function (User $user) {
    $school = School::factory()->create();
    $year = fake()->year('-10 years');
    session(['school_id' => $school->id]);
    $yearAcademy = ($year - 1) . " - " . $year + 1;
    $data = [
        'school_id' => $school->id,
        'academic_year_name' => $yearAcademy,
        'status_years' => AcademicYear::STATUS_CLOSED
    ];

    $this->actingAs($user)
        ->post(route('academy-year.store'), $data)->assertRedirect(route('academy-year.index'));
    $this->assertDatabaseHas('academic_years', $data);
})->with('staff_can_crud');


/**
 * Read
 *
 */

it('can render Academy Years index Datatable page as ', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('datatable.academy-year'));

    $response->assertOk();
})->with('staff_only_read');

it('can render Academy Years page index page as ', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('academy-year.index'));

    $response->assertOk();
})->with('staff_only_read');



/**
 * UPDATE RENDER
 */

it('can render page edit Academy Years  as ', function (User $user) {
    $academyYear = AcademicYear::factory()->create();
    session(['school_id' => $academyYear->school_id]);
    $this->actingAs($user)
        ->get(route('academy-year.edit', ['academy_year' => $academyYear->id]))->assertOk();
})->with('staff_can_crud');

/**
 * UPDATE VALIDATION
 */

it('can render Academy Years update invalid required school_id and name as ', function (User $user) {
    $academyYear = AcademicYear::factory()->create();
    session(['school_id' => $academyYear->school_id]);
    $this->actingAs($user)
        ->put(route('academy-year.update', ['academy_year' => $academyYear->id]), [
            'school_id' => '',
            'academic_year_name' => '',
        ])
        ->assertInvalid([
            'school_id',
            'academic_year_name'
        ]);
})->with('staff_can_crud');

it('can render Academy Years update invalid academy years formatted  as ', function (User $user) {
    $academyYear = AcademicYear::factory()->create();
    session(['school_id' => $academyYear->school_id]);
    $this->actingAs($user)
        ->put(route('academy-year.update', ['academy_year' => $academyYear->id]), [
            'school_id' => $academyYear->school_id,
            'academic_year_name' => fake()->name(),
        ])
        ->assertInvalid(['academic_year_name']);
})->with('staff_can_crud');


/**
 * UPDATE
 */

it('can render Academy Years update data  as ', function (User $user) {
    $academyYear = AcademicYear::factory()->create();
    session(['school_id' => $academyYear->school_id]);
    $year = fake()->year('-10 years');
    $yearAcademy = $year . "-" . $year + 1;

    $data = [
        'school_id' => $academyYear->school_id,
        'academic_year_name' => $yearAcademy,
        'status_years' => AcademicYear::STATUS_CLOSED
    ];

    $this->actingAs($user)
        ->put(route('academy-year.update', ['academy_year' => $academyYear->id]), $data)
        ->assertRedirect(route('academy-year.index'));
    $this->assertDatabaseHas('academic_years', $data);
})->with('staff_can_crud');

/**
 * DELETE
 */
it('can render Academy Years delete data  as ', function (User $user) {
    $academyYear = AcademicYear::factory()->create();
    session(['school_id' => $academyYear->school_id]);
    $this->actingAs($user)
        ->delete(route('academy-year.destroy', ['academy_year' => $academyYear->id]))
        ->assertOk();
})->with('staff_can_crud');


/**
 * NEGATIVE CRUD
 */

it("can't render Academy Years create page as ", function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('academy-year.create'));

    $response->assertNotFound();
})->with('staff_cannot_crud');


it("can't render Academy Years Edit page as ", function (User $user) {
    $academyYear = AcademicYear::factory()->create();
    $response = $this
        ->actingAs($user)
        ->get(route('academy-year.edit', $academyYear->getKey()));
    $response->assertNotFound();
})->with('staff_cannot_crud');

it("can't render Academy Years store  as ", function (User $user) {
    $school = School::factory()->create();
    $this->actingAs($user)
        ->post(route('academy-year.store'), [
            'school_id' => $school->id,
            'academic_year_name' => fake()->name(),
        ])->assertNotFound();
})->with('staff_cannot_crud');

it("can't render Academy Years update  page as ", function (User $user) {
    $tuitionType = AcademicYear::factory()->create();
    $name = $this->faker()->name();
    $this->actingAs($user)
        ->put(route('academy-year.update', $tuitionType->getKey()), [
            'school_id' => $tuitionType->school_id,
            'academic_year_name' => $name,
        ])->assertNotFound();
})->with('staff_cannot_crud');

it("can't render Academy Years delete data  as ", function (User $user) {
    $academyYear = AcademicYear::factory()->create();
    $this->actingAs($user)
        ->delete(route('academy-year.destroy', ['academy_year' => $academyYear->id]))
        ->assertNotFound();
})->with('staff_cannot_crud');
