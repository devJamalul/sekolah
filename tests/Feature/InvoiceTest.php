<?php

use App\Models\School;
use App\Models\User;

beforeEach(function () {
    session(['school_id' => 2]);

    $this->superAdmin = User::role(User::ROLE_SUPER_ADMIN)->first();
    $this->opsAdmin = User::role(User::ROLE_OPS_ADMIN)->first();

    // $this->adminSekolah = User::role(User::ROLE_ADMIN_SEKOLAH)->first();
    $this->adminSekolah = User::role(User::ROLE_ADMIN_SEKOLAH)->firstWhere([
        'school_id' => session('school_id')
    ]);

    // $this->bendahara = User::role(User::ROLE_BENDAHARA)->first();
    $this->bendahara = User::role(User::ROLE_BENDAHARA)->firstWhere([
        'school_id' => session('school_id')
    ]);

    // $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->first();
    $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->firstWhere([
        'school_id' => session('school_id')
    ]);

    // $this->kepalaSekolah = User::role(User::ROLE_KEPALA_SEKOLAH)->first();
    $this->kepalaSekolah = User::role(User::ROLE_KEPALA_SEKOLAH)->firstWhere([
        'school_id' => session('school_id')
    ]);

    $this->setupFaker();
});

test('can render invoice page as Sempoa Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.index'));

    $response->assertOk();
})
    ->with('sempoa_staff');

test('can render invoice page as School Staff', function (User $user) {
    info(session('school_id'));
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.index'));

    $response->assertOk();
})
    ->with('school_staff');

test('can render invoice create page as Sempoa Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.create'));

    $response->assertOk();
})
    ->with('sempoa_staff');

test('can render invoice create page as School Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.create'));

    $response->assertOk();
})
    ->with([
        User::ROLE_BENDAHARA => [fn () => $this->bendahara],
        User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    ]);

test('can not render invoice create page as School Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.create'));

    $response->assertNotFound();
})
    ->with([
        User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
        User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    ]);

test('can store new invoice with empty invoice number', function () {
    $data = [
        'note' => 'Invoice baru',
        'invoice_date' => now(),
        'due_date' => now()->addDay()
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->get(route('invoices.store', $data));

    $response->assertRedirect(route('invoices.index'));
    $this->assertDatabaseHas('invoices', $data);
});

test('can store new invoice with empty invoice number', function () {
    $data = [
        'note' => 'Invoice baru',
        'invoice_number' => str()->rand(100, 900),
        'invoice_date' => now(),
        'due_date' => now()->addDay()
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->get(route('invoices.store', $data));

    $response->assertRedirect(route('invoices.index'));
    $this->assertDatabaseHas('invoices', $data);
});
