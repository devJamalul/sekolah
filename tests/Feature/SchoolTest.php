<?php

use App\Models\School;
use App\Models\Staff;
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

// Create

test('can render School create page as Super Admin', function () {
    $response = $this
        ->actingAs($this->superAdmin)
        ->get(route('schools.create'));

    $response->assertOk();
});

it('requires field the school information', function () {
    $name = $this->faker()->company();
    $this->actingAs($this->superAdmin)
        ->post(route('schools.store'), [
            [
                'school_name' => '',
                'province' => '',
                'city' => '',
                'postal_code' => '',
                'address' => '',
                'grade' => '',
                'email' => '',
                'phone' => '',
                'foundation_head_name' => fake()->name(),
                'foundation_head_tlpn' => fake()->phoneNumber(),
                'foundation_head_email' => fake()->email(),
                'name_pic' => fake()->name(),
                'email_pic' => fake()->email()
            ]
        ])->assertInvalid(
            [
                'school_name',
                'province',
                'city',
                'postal_code',
                'address',
                'grade',
                'email',
                'phone'
            ]

        );
});

it('requires field the school foundation', function () {
    $name = $this->faker()->company();
    $this->actingAs($this->superAdmin)
        ->post(route('schools.store'), [
            [
                'school_name' => fake()->name(),
                'province' => fake()->city(),
                'city' => fake()->city(),
                'postal_code' => fake()->postcode(),
                'address' => fake()->address(),
                'grade' => fake()->randomElement(School::GRADE_SCHOOL),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'foundation_head_name' => '',
                'foundation_head_tlpn' => '',
                'foundation_head_email' => '',
                'name_pic' => fake()->name(),
                'email_pic' => fake()->email()
            ]
        ])->assertInvalid(
            [
                'foundation_head_name',
                'foundation_head_tlpn',
                'foundation_head_email',
            ]

        );
});

it('requires field the school pic', function () {
    $name = $this->faker()->company();
    $this->actingAs($this->superAdmin)
        ->post(route('schools.store'), [
            [
                'school_name' => fake()->name(),
                'province' => fake()->city(),
                'city' => fake()->city(),
                'postal_code' => fake()->postcode(),
                'address' => fake()->address(),
                'grade' => fake()->randomElement(School::GRADE_SCHOOL),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'foundation_head_name' => fake()->name(),
                'foundation_head_tlpn' => fake()->phoneNumber(),
                'foundation_head_email' => fake()->email(),
                'name_pic' => '',
                'email_pic' => ''
            ]
        ])->assertInvalid(
            [
                'name_pic',
                'email_pic',
            ]

        );
});



test('can create new School', function () {
    $data = [
        'school_name' => fake()->name(),
        'province' => fake()->city(),
        'city' => fake()->city(),
        'postal_code' => fake()->postcode(),
        'address' => fake()->address(),
        'grade' => fake()->randomElement(School::GRADE_SCHOOL),
        'email' => fake()->email(),
        'phone' => fake()->phoneNumber(),
        'foundation_head_name' => fake()->name(),
        'foundation_head_tlpn' => fake()->phoneNumber(),
        'foundation_head_email' => fake()->email(),
        'name_pic' => fake()->name(),
        'email_pic' => fake()->email()
    ];

    $this->actingAs($this->superAdmin)
        ->post(route('schools.store'), $data)
        ->assertRedirect(route('schools.index'));

    dd(session());
    $this->assertDatabaseHas('schools', $data);

    $this->assertDatabaseHas('staff', [
        'name' => $data['name_pic'],
    ]);

    $user = User::firstWhere([
        'email' => $data['email_pic']
    ]);
    expect($user->hasRole(User::ROLE_ADMIN_SEKOLAH))->toBeTrue();
});

// Read
test('can render School index page as Sempoa Staff', function (User $user) {
    $school = School::factory()->create();
    $response = $this
        ->actingAs($user)
        ->get(route('schools.index'));

    $response->assertOk();
    $this->assertModelExists($school);
})->with('sempoa_staff');

// Update
test('can render edit page as Sempoa Staff', function (User $user) {
    $school = School::factory()->create();
    $_user = User::factory()->create([
        'school_id' => $school->getKey()
    ]);
    $_staff = Staff::factory()->create([
        'school_id' => $school->getKey(),
        'user_id' => $_user->getKey()
    ]);
    $school->staff_id = $_staff->getKey();
    $school->save();

    $response = $this
        ->actingAs($user)
        ->get(route('schools.edit', $school->getKey()));

    $response->assertOk();
})->with('sempoa_staff');

test('can edit school as Sempoa Staff', function (User $user) {
    $school = School::factory()->create();
    $_user = User::factory()->create([
        'school_id' => $school->getKey()
    ]);
    $_staff = Staff::factory()->create([
        'school_id' => $school->getKey(),
        'user_id' => $_user->getKey()
    ]);
    $school->staff_id = $_staff->getKey();
    $school->save();

    $name = "Yayasan Edited";

    $this->actingAs($user)
        ->put(route('schools.update', $school->getKey()), [
            'name' => $name
        ])->assertRedirect(route('schools.index'));

    $this->assertDatabaseHas('schools', [
        'name' => $name
    ]);
})->with('sempoa_staff');

// Delete
test('can delete School as Super Admin', function () {
    $school = School::factory()->create();

    $this->actingAs($this->superAdmin)
        ->delete(route('schools.destroy', $school->getKey()))
        ->assertOk();

    $this->assertSoftDeleted($school);
});

// Negasi
// Create
test('can not render School create page as Ops Admin', function () {
    $response = $this
        ->actingAs($this->opsAdmin)
        ->get(route('schools.create'));

    $response->assertNotFound();
});

test('can not create school as Ops Admin', function () {
    $data = [
        'school_id' => '',
        'name' => fake()->name(),
        'staff_id' => Staff::factory()->create()
    ];
    $response = $this
        ->actingAs($this->opsAdmin)
        ->post(route('schools.store'), $data);

    $response->assertNotFound();
});

test('can not render School create page as School Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('schools.create'));

    $response->assertNotFound();
})->with('school_staff');

test('can not create school as School Staff', function (User $user) {
    $data = [
        'school_id' => '',
        'name' => fake()->name(),
        'staff_id' => Staff::factory()->create()
    ];
    $response = $this
        ->actingAs($user)
        ->post(route('schools.store'), $data);

    $response->assertNotFound();
})->with('school_staff');

// Read
it('forbid guest to view School page', function () {
    $this
        ->get(route('schools.index'))
        ->assertNotFound();
});

test('can not render School index page as School Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('schools.index'));

    $response->assertNotFound();
})->with('school_staff');

// Update
test('can not render School edit page as School Staff', function (User $user) {
    $school = School::factory()->create();
    $_user = User::factory()->create([
        'school_id' => $school->getKey()
    ]);
    $_staff = Staff::factory()->create([
        'school_id' => $school->getKey(),
        'user_id' => $_user->getKey()
    ]);
    $school->staff_id = $_staff->getKey();
    $school->save();

    $response = $this
        ->actingAs($user)
        ->get(route('schools.edit', $school->getKey()));

    $response->assertNotFound();
})->with('school_staff');

// Delete
test('can not delete School as Ops Admin', function () {
    $school = School::factory()->create();

    $response = $this->actingAs($this->opsAdmin)
        ->delete(route('schools.destroy', $school->getKey()));

    $response->assertNotFound();
});

test('can not delete School as School staff', function (User $user) {
    $school = School::factory()->create();

    $response = $this->actingAs($user)
        ->delete(route('schools.destroy', $school->getKey()));

    $response->assertNotFound();
})->with('school_staff');
