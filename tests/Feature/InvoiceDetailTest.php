<?php

use App\Models\Invoice;
use App\Models\User;

beforeEach(function () {
    session(['school_id' => 2]);

    $this->superAdmin = User::role(User::ROLE_SUPER_ADMIN)->first();
    $this->opsAdmin = User::role(User::ROLE_OPS_ADMIN)->first();

    $this->adminSekolah = User::role(User::ROLE_ADMIN_SEKOLAH)->firstWhere([
        'school_id' => session('school_id')
    ]);

    $this->bendahara = User::role(User::ROLE_BENDAHARA)->firstWhere([
        'school_id' => session('school_id')
    ]);

    // $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->first();
    $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->firstWhere([
        'school_id' => session('school_id')
    ]);

    $this->kepalaSekolah = User::role(User::ROLE_KEPALA_SEKOLAH)->firstWhere([
        'school_id' => session('school_id')
    ]);

    $this->setupFaker();
});

// C R E A T E
test('C R E A T E', function () {
    expect(true)->toBeTrue();
});

test('store invoice detail validation - item_name', function () {
    $note = str()->random(10);
    $data = [
        'school_id' => session('school_id'),
        'invoice_number' => $note,
        'note' => $note,
        'invoice_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDay()->format('Y-m-d'),
    ];
    $this
        ->actingAs($this->bendahara)
        ->post(route('invoices.store', $data));

    $invoice = Invoice::firstWhere('invoice_number', $note);

    // invoice detail
    $data = [
        'invoice_id' => $invoice->getKey(),
        'price' => 15000
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoice-details.store', $invoice->getKey()), $data);

    $response->assertInvalid(['item_name']);
});

test('store invoice detail validation - price', function () {
    $note = str()->random(10);
    $data = [
        'school_id' => session('school_id'),
        'invoice_number' => $note,
        'note' => $note,
        'invoice_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDay()->format('Y-m-d'),
    ];
    $this
        ->actingAs($this->bendahara)
        ->post(route('invoices.store', $data));

    $invoice = Invoice::firstWhere('invoice_number', $note);

    // invoice detail
    $item_name = "Barang #" . str()->random(2);
    $data = [
        'invoice_id' => $invoice->getKey(),
        'item_name' => $item_name,
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoice-details.store', $invoice->getKey()), $data);

    $response->assertInvalid(['price']);
});

test('can store new invoice with details', function () {
    $inv_number = str()->random(10);
    $data = [
        'school_id' => session('school_id'),
        'note' => $inv_number,
        'invoice_number' => $inv_number,
        'invoice_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDay()->format('Y-m-d')
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoices.store', $data));

    $this->assertDatabaseHas('invoices', $data);
    $invoice = Invoice::firstWhere('invoice_number', $inv_number);
    $response->assertRedirectContains($invoice->getKey() . '/detail');

    // invoice detail
    $item_name = "Barang #" . str()->random(2);
    $data = [
        'invoice_id' => $invoice->getKey(),
        'item_name' => $item_name,
        'price' => 15000
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoice-details.store', $invoice->getKey()), $data);
    $this->assertDatabaseHas('invoice_details', $data);
});
