<?php

use App\Models\User;
use App\Models\Grade;
use App\Models\School;
use App\Models\AcademicYear;
use App\Models\TuitionType;

beforeEach(function () {
    $this->superAdmin = User::role(User::ROLE_SUPER_ADMIN)->first();
    $this->opsAdmin = User::role(User::ROLE_OPS_ADMIN)->first();
    $this->adminYayasan = User::role(User::ROLE_ADMIN_YAYASAN)->first();
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
it('forbid guest to view Wallet page', function () {
    $this
        ->get(route('wallet.index'))
        ->assertNotFound();
});

// Render Create
it('can render Wallet create page', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('wallet.create'));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can create new Wallet', function (User $user) {
    $school = School::factory()->create();
    $name = fake()->word();
    $randomNumber = rand(1, 2000);

    $this->actingAs($user)
        ->post(route('wallet.store'), [
            'school_id'     => $school->getKey(),
            'name'          => $name,
            'init_value'    => $randomNumber,
        ]);
    
    $this->assertDatabaseHas('wallets', [
        'name'          => $name,
        'init_value'    => $randomNumber,
    ]); 
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

// Render Index 
it('can render Wallet index page', function (User $user) {
    $response = $this->actingAs($user)
                    ->get(route('wallet.index'));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

// Render Update 
it('can render Expense edit page', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $name = fake()->word();
    $randomNumber = rand(1, 2000);
    
    $wallet = $school->wallets()->create([
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);

    $this->assertDatabaseHas('wallets', [
        'id' => $wallet->getKey(),
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);

    $response = $this->actingAs($user)
                    ->get(route('wallet.edit', $wallet->getKey()));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can edit Wallet', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $name = fake()->word();
    $randomNumber = rand(1, 2000);
    
    $wallet = $school->wallets()->create([
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);

    $this->actingAs($user)
            ->put(route('wallet.update', $wallet->getKey()),[
                'school_id' => $school->getKey(),
                'name' => $name,
                'init_value' => $randomNumber
            ])
        ->assertRedirect(route('wallet.index'));

    $this->assertDatabaseHas('wallets', [
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);

})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

// Render Delete
it('can delete Wallet', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $name = fake()->word();
    $randomNumber = rand(1, 2000);

    $wallet = $school->wallets()->create([
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);

    $this->actingAs($user)
        ->delete(route('wallet.destroy', $wallet->getKey()))
        ->assertStatus(200);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);


// Negation CRUD
it('can not render Wallet create page', function (User $user) {
    $response = $this->actingAs($user)
                    ->get(route('wallet.create'));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

it('can not create new Wallet with Invalid requires', function (User $user) {
    $this->actingAs($user)
        ->post(route('wallet.store'))
        ->assertSessionHasErrors(['name', 'init_value']);
        
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can not render Wallet edit page', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $name = fake()->word();
    $randomNumber = rand(1, 2000);


    $wallet = $school->wallets()->create([
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);

    $response = $this->actingAs($user)
                    ->get(route('wallet.edit', $wallet->getKey()));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

it('can not edit Wallet with Invalid requires', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $name = fake()->word();
    $randomNumber = rand(1, 2000);

    $wallet = $school->wallets()->create([
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);

    $this->actingAs($user)
        ->put(route('wallet.update', $wallet->getKey()), [
            'name' => '',
        ])  
        ->assertSessionHasErrors(['name']);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can not delete Wallet', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $name = fake()->word();
    $randomNumber = rand(1, 2000);

    $wallet = $school->wallets()->create([
        'school_id' => $school->getKey(),
        'name' => $name,
        'init_value' => $randomNumber
    ]);


    $response = $this->actingAs($user)
                    ->delete(route('wallet.destroy', $wallet->getKey()));
    
    $response->assertNotFound();

})->with([
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);
