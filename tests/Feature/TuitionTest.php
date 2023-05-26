<?php

use App\Models\User;
use App\Models\Grade;
use App\Models\School;
use App\Models\AcademicYear;
use App\Models\TuitionType;

beforeEach(function () {
    $this->superAdmin = User::role(User::ROLE_SUPER_ADMIN)->first();
    $this->opsAdmin = User::role(User::ROLE_OPS_ADMIN)->first();
    $this->adminSekolah = User::role(User::ROLE_ADMIN_SEKOLAH)->first();
    $this->bendahara = User::role(User::ROLE_BENDAHARA)->first();
    $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->first();
    $this->kepalaSekolah = User::role(User::ROLE_KEPALA_SEKOLAH)->first();
    $this->siswa = User::role(User::ROLE_SISWA)->first();
    $this->alumni = User::role(User::ROLE_ALUMNI)->first();
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
it('forbid guest to view Tuition page', function () {
    $this
        ->get(route('tuition.index'))
        ->assertNotFound();
})->todo();

// Render Create
it('can render Tuition create page', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('tuition.create'));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can create new Tuition', function (User $user) {
    $school = School::factory()->create();
    $tuitionType = TuitionType::factory()->create();
    $academicYear = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();
    $price = $this->faker()->numberBetween(1, 100);
    $requestApprovedBy = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tuition.store'), [
            'school_id' => $school->getKey(),
            'tuition_type_id' => $tuitionType->getKey(),
            'academic_year_id' => $academicYear->getKey(),
            'grade_id' => $grade->getKey(),
            'price' => $price,
            'requested_by' => $requestApprovedBy->getKey(),
            'approved_by' => $requestApprovedBy->getKey(),
        ])
        ->assertRedirect(route('tuition.index'));

    $this->assertDatabaseHas('tuitions', [
        'price' => $price,
    ]);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

// Render Index
it('can render Tuition index page', function (User $user) {
    $response = $this->actingAs($user)
                    ->get(route('tuition.index'));

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
it('can render Tuition edit page', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $tuitionType = TuitionType::factory()->create(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $price = $this->faker()->numberBetween(1, 100);
    $requestApprovedBy = User::factory()->create();

    $tuition = $school->tuitions()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuitionType->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'price' => $price,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->assertDatabaseHas('tuitions', [
        'id' => $tuition->getKey(),
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuitionType->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'price' => $price,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $response = $this->actingAs($user)
                    ->get(route('tuition.edit', $tuition->getKey()));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can edit Tuition', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $tuitionType = TuitionType::factory()->create(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $price = $this->faker()->numberBetween(1, 100);
    $requestApprovedBy = User::factory()->create();

    $tuition = $school->tuitions()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuitionType->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'price' => $price,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->actingAs($user)
            ->put(route('tuition.update', $tuition->getKey()),[
                'school_id' => $tuition->school_id,
                'tuition_type_id' => $tuition->tuition_type_id,
                'academic_year_id' => $tuition->academic_year_id,
                'grade_id' => $tuition->grade_id,
                'price' => $tuition->price,
                'requested_by' => $tuition->request_by,
                'approved_by' => $tuition->approval_by,
            ])
        ->assertRedirect(route('tuition.index'));

    $this->assertDatabaseHas('tuitions', [
        'price' => $tuition->price
    ]);

})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

// Render Delete
it('can delete Tuition', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $tuitionType = TuitionType::factory()->create(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $price = $this->faker()->numberBetween(1, 100);
    $requestApprovedBy = User::factory()->create();

    $tuition = $school->tuitions()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuitionType->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'price' => $price,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->actingAs($user)
        ->delete(route('tuition.destroy', $tuition->getKey()))
        ->assertStatus(200);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);


// Negation CRUD
it('can not render Tuition create page', function (User $user) {
    $response = $this->actingAs($user)
                    ->get(route('tuition.create'));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

it('can not create new Tuition with Invalid requires', function (User $user) {
    $this->actingAs($user)
        ->post(route('tuition.store'))
        ->assertSessionHasErrors(['tuition_type_id', 'academic_year_id', 'grade_id', 'price', 'requested_by', 'approved_by']);

})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
])->todo();

it('can not render Tuition edit page', function (User $user) {
    $school = School::factory()->create();
    $tuitionType = TuitionType::factory()->create();
    $academicYear = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();
    $price = $this->faker()->numberBetween(1, 100);
    $requestApprovedBy = User::factory()->create();


    $tuition = $school->tuitions()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuitionType->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'price' => $price,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $response = $this->actingAs($user)
                    ->get(route('tuition.edit', $tuition->getKey()));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

it('can not edit Tuition with Invalid requires', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $tuitionType = TuitionType::factory()->create(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $price = $this->faker()->numberBetween(1, 100);
    $requestApprovedBy = User::factory()->create();

    $tuition = $school->tuitions()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuitionType->getKey(),
        'academic_year_id' => $academicYear->getKey(),
        'grade_id' => $grade->getKey(),
        'price' => $price,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->actingAs($user)
        ->put(route('tuition.update', $tuition->getKey()), [
            'school_id' => '',
            'tuition_type_id' => '',
            'academic_year_id' => '',
            'grade_id' => '',
            'price' => '',
            'request_by' => '',
            'approval_by' => ''
        ])
        ->assertSessionHasErrors(['tuition_type_id', 'academic_year_id', 'grade_id', 'price', 'requested_by', 'approved_by']);;
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
])->todo();

it('can not delete Tuition', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $tuitionType = TuitionType::factory()->create(['school_id' => $school->getKey()]);
    $academicYear = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $period = now();
    $price = $this->faker()->numberBetween(1, 100);
    $tuition = $school->tuitions()->create();

    $response = $this->actingAs($user)
                    ->delete(route('tuition.destroy', $tuition->getKey()));

    $response->assertNotFound();

})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);
