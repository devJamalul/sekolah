<?php

use App\Models\Grade;
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

// Create
test('can render create page as Sempoa Staff', function (User $user) {
    session(['school_id' => session('school_id')]);

    $this->actingAs($user)
        ->get(route('grade.create'))
        ->assertOk();
})->with('sempoa_staff');

test('can render create page as School Staff', function (User $user) {
    session(['school_id' => $user->getKey()]);

    $this->actingAs($user)
        ->get(route('grade.create'))
        ->assertOk();
})->with([
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

it('requires the grade name on create', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('grade.store'), [
            'school_id' => session('school_id'),
            'grade_name' => '',
        ]);

    $response->assertInvalid(['grade_name']);
});

test('can create grade as Sempoa Staff', function (User $user) {
    $data = [
        'school_id' => session('school_id'),
        'grade_name' => fake()->name()
    ];
    $response = $this->actingAs($user)
        ->post(route('grade.store'), $data);

    $response->assertRedirect(route('grade.index'));
    $this->assertDatabaseHas('grades', $data);
})->with('sempoa_staff');

test('can create grade as School Staff', function (User $user) {
    $data = [
        'school_id' => session('school_id'),
        'grade_name' => fake()->name()
    ];
    $response = $this->actingAs($user)
        ->post(route('grade.store'), $data);

    $response->assertRedirect(route('grade.index'));
    $this->assertDatabaseHas('grades', $data);
})->with([
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

// Read
test('can render index page as Sempoa Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $this->actingAs($user)
        ->get(route('grade.index'))
        ->assertOk();

    $this->assertModelExists($grade);
})->with('sempoa_staff');

test('can render index page as School Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $this->actingAs($user)
        ->get(route('grade.index'))
        ->assertOk();

    $this->assertModelExists($grade);
})->with('school_staff');

// Update
test('can render edit page as Sempoa Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $response = $this->actingAs($user)
        ->get(route('grade.edit', $grade->getKey()))
        ->assertOk();

    $response->assertSee($grade->grade_name);
})->with('sempoa_staff');

test('can render edit page as School Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => $user->school_id]);

    $response = $this->actingAs($user)
        ->get(route('grade.edit', $grade->getKey()))
        ->assertOk();

    $response->assertSee($grade->grade_name);
})->with([
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

it('requires the grade name on update', function () {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);
    $this->actingAs($this->superAdmin)
        ->put(route('grade.update', $grade->getKey()), [
            'grade_name' => '',
        ])->assertInvalid(['grade_name']);
});

test('can update grade as Sempoa Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $data = [
        'school_id' => session('school_id'),
        'grade_name' => fake()->name()
    ];
    $response = $this->actingAs($user)
        ->put(route('grade.update', $grade->getKey()), $data);

    $response->assertRedirect(route('grade.index'));
    $this->assertDatabaseHas('grades', $data);
})->with('sempoa_staff');

test('can update grade as School Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $data = [
        'school_id' => session('school_id'),
        'grade_name' => fake()->name()
    ];
    $response = $this->actingAs($user)
        ->put(route('grade.update', $grade->getKey()), $data);

    $response->assertRedirect(route('grade.index'));
    $this->assertDatabaseHas('grades', $data);
})->with([
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

// Delete
test('can delete grade as Sempoa Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $response = $this->actingAs($user)
        ->delete(route('grade.destroy', $grade->getKey()));

    $response->assertStatus(200);
    $this->assertSoftDeleted($grade);
})->with('sempoa_staff');

test('can delete grade as School Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $response = $this->actingAs($user)
        ->delete(route('grade.destroy', $grade->getKey()));

    $response->assertStatus(200);
    $this->assertSoftDeleted($grade);
})->with([
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

// Negasi
// Create
test('can not render create page as School Staff', function (User $user) {
    $response = $this->actingAs($user)
    ->get(route('grade.create'));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

test('can not create grade as School Staff', function (User $user) {
    $data = [
        'school_id' => session('school_id'),
        'grade_name' => fake()->name()
    ];
    $response = $this->actingAs($user)
    ->post(route('grade.store'), $data);

    $response->assertNotFound();
    $this->assertDatabaseMissing('grades', $data);
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

// Read

// Update
test('can not render edit page as School Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $response = $this->actingAs($user)
    ->get(route('grade.edit', $grade->getKey()));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

test('can not update grade as School Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $data = [
        'school_id' => session('school_id'),
        'grade_name' => fake()->name()
    ];
    $response = $this->actingAs($user)
        ->put(route('grade.update', $grade->getKey()), $data);

    $response->assertNotFound();
    $this->assertDatabaseMissing('grades', $data);
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

// Delete
test('can not delete grade as School Staff', function (User $user) {
    $grade = Grade::factory()->create(['school_id' => session('school_id')]);

    $response = $this->actingAs($user)
    ->delete(route('grade.destroy', $grade->getKey()));

    $response->assertNotFound();
    $this->assertModelExists($grade);
})->with([
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);
