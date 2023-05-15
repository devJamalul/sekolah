<?php

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\School;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

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

// CREATE
test('C R E A T E', function () {
    expect(true)->toBeTrue();
});
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

test('store invoice validation - note', function () {
    $data = [
        'school_id' => session('school_id'),
        'invoice_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDay()->format('Y-m-d')
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoices.store', $data));

    $response->assertInvalid(['note']);
});

test('store invoice validation - invoice_date', function () {
    $note = str()->random(10);
    $data = [
        'school_id' => session('school_id'),
        'note' => $note,
        'due_date' => now()->addDay()->format('Y-m-d')
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoices.store', $data));

    $response->assertInvalid(['invoice_date']);
});

test('store invoice validation - due_date', function () {
    $note = str()->random(10);
    $data = [
        'school_id' => session('school_id'),
        'note' => $note,
        'invoice_date' => now()->format('Y-m-d'),
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoices.store', $data));

    $response->assertInvalid(['due_date']);
});

test('can store new invoice with empty invoice number', function () {
    $inv_number = str()->random(10);
    $data = [
        'school_id' => session('school_id'),
        'note' => $inv_number,
        'invoice_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDay()->format('Y-m-d')
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->post(route('invoices.store', $data));

    $this->assertDatabaseHas('invoices', $data);
    $invoice = Invoice::firstWhere('note', $inv_number);
    $response->assertRedirectContains($invoice->getKey() . '/detail');
    expect($invoice->is_posted)->toBe(Invoice::POSTED_DRAFT);
});

test('can store new invoice with invoice number', function () {
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
    expect($invoice->is_posted)->toBe(Invoice::POSTED_DRAFT);
});

// READ
test('R E A D', function () {
    expect(true)->toBeTrue();
});
test('can render invoice page as Sempoa Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.index'));

    $response->assertOk();
})
    ->with('sempoa_staff');

test('can render invoice page as School Staff', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.index'));

    $response->assertOk();
})
    ->with('school_staff');

// UPDATE
test('U P D A T E', function () {
    expect(true)->toBeTrue();
});
test('can render invoice edit page as Sempoa Staff', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // edit
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertOk();
})
    ->with('sempoa_staff');

test('can render invoice edit page as School Staff', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // edit
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertOk();
})
    ->with([
        User::ROLE_BENDAHARA => [fn () => $this->bendahara],
        User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    ]);

test('can not render invoice edit page as School Staff', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // edit
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertNotFound();
})
    ->with([
        User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
        User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    ]);

test('update invoice validation - note', function () {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // edit
    $response = $this
        ->actingAs($this->bendahara)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertOk();

    // update
    $data = [
        'invoice_number' => $invoice->invoice_number,
        'invoice_date' => $invoice->invoice_date,
        'due_date' => $invoice->due_date,
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->put(route('invoices.update', ['invoice' => $invoice->getKey()]), $data);

    $response->assertInvalid(['note']);
});

test('update invoice validation - invoice_number', function () {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // edit
    $response = $this
        ->actingAs($this->bendahara)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertOk();

    // update
    $new_note = "updated #" . str()->random(10);
    $data = [
        'note' => $new_note,
        'invoice_date' => $invoice->invoice_date,
        'due_date' => $invoice->due_date,
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->put(route('invoices.update', ['invoice' => $invoice->getKey()]), $data);

    $response->assertInvalid(['invoice_number']);
});

test('update invoice validation - invoice_date', function () {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // edit
    $response = $this
        ->actingAs($this->bendahara)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertOk();

    // update
    $new_note = "updated #" . str()->random(10);
    $data = [
        'note' => $new_note,
        'invoice_number' => $invoice->invoice_number,
        'due_date' => $invoice->due_date,
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->put(route('invoices.update', ['invoice' => $invoice->getKey()]), $data);

    $response->assertInvalid(['invoice_date']);
});

test('update invoice validation - due_date', function () {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // edit
    $response = $this
        ->actingAs($this->bendahara)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertOk();

    // update
    $new_note = "updated #" . str()->random(10);
    $data = [
        'note' => $new_note,
        'invoice_number' => $invoice->invoice_number,
        'invoice_date' => $invoice->invoice_date,
    ];
    $response = $this
        ->actingAs($this->bendahara)
        ->put(route('invoices.update', ['invoice' => $invoice->getKey()]), $data);

    $response->assertInvalid(['due_date']);
});

test('can update invoice', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();
    expect($invoice->is_posted)->toBe(Invoice::POSTED_DRAFT);

    // edit
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.edit', ['invoice' => $invoice->getKey()]));

    $response->assertOk();

    // update
    $new_note = "updated #" . str()->random(10);
    $data = [
        'note' => $new_note,
        'invoice_number' => $invoice->invoice_number,
        'invoice_date' => $invoice->invoice_date,
        'due_date' => $invoice->due_date,
    ];
    $response = $this
        ->actingAs($user)
        ->put(route('invoices.update', ['invoice' => $invoice->getKey()]), $data);

    $this->assertDatabaseHas('invoices', $data);
    $invoice->refresh();
    expect($invoice->is_posted)->toBe(Invoice::POSTED_DRAFT);
})
    ->with([
        User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
        User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
        User::ROLE_BENDAHARA => [fn () => $this->bendahara],
        User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    ]);

// DELETE
test('D E L E T E', function () {
    expect(true)->toBeTrue();
});

test('can delete invoice', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();
    expect($invoice->is_posted)->toBe(Invoice::POSTED_DRAFT);

    // delete
    $this->actingAs($user)->delete(route('invoices.destroy', ['invoice' => $invoice->getKey()]));

    // assert
    $invoice->refresh();
    $this->assertSoftDeleted($invoice);
})
    ->with([
        User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
        User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
        User::ROLE_BENDAHARA => [fn () => $this->bendahara],
        User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    ]);

// VOID
test('V O I D', function () {
    expect(true)->toBeTrue();
});

test('can render void invoice page', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // void
    $response = $this
        ->actingAs($user)
        ->get(route('invoices.void', ['invoice' => $invoice->getKey()]));

    $response->assertOk();
})
    ->with([
        User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
        User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
        User::ROLE_BENDAHARA => [fn () => $this->bendahara],
        User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    ]);

test('can void invoice', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    // void
    $this->actingAs($user)
        ->get(route('invoices.void', ['invoice' => $invoice->getKey()]));

    $response = $this
        ->actingAs($user)
        ->post(route('invoices.voidment', ['invoice' => $invoice->getKey()]));

    // password confirmation
    $response->assertRedirectContains('user/confirm-password');
    $this->actingAs($user)->post(route('password.confirm'), ['password' => 'password']);
    // $response->assertRedirectContains('void');

    $response = $this
        ->actingAs($user)
        ->post(route('invoices.voidment', ['invoice' => $invoice->getKey()]));

    // asserts
    $invoice->refresh();
    $this->assertModelExists($invoice);
    $this->assertNotSoftDeleted($invoice);
    expect($invoice->is_posted)->toBe(Invoice::VOID);
})
    ->with([
        User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
        User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
        User::ROLE_BENDAHARA => [fn () => $this->bendahara],
        User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    ]);

test('can not void a voided invoice', function () {
    $invoice = Invoice::factory()->create([
        'is_posted' => Invoice::VOID
    ]);
    $invoice->refresh();

    // void
    $response = $this->actingAs($this->superAdmin)
        ->post(route('invoices.voidment', ['invoice' => $invoice->getKey()]));

    // password confirmation
    $response->assertRedirectContains('user/confirm-password');
    $this->actingAs($this->superAdmin)->post(route('password.confirm'), ['password' => 'password']);

    // void confirmation
    $response = $this->actingAs($this->superAdmin)
        ->post(route('invoices.voidment', ['invoice' => $invoice->getKey()]));

    $invoice->refresh();
    expect($invoice->is_posted)->toBe(Invoice::VOID);

    // re-void
    $this->actingAs($this->superAdmin)
        ->post(route('invoices.voidment', ['invoice' => $invoice->getKey()]));

    // assert error
    $this->assertTrue(session()->has('alert'));
    info(session('alert'));
    // $this->assertEquals('error', session('alert')['type']);
});

// PUBLISH
test('P U B L I S H', function () {
    expect(true)->toBeTrue();
});

test('can not publish invoice', function (User $user) {
    $invoice = Invoice::factory()->create();
    $invoice->refresh();

    $this->assertModelExists($invoice);
    expect($invoice->is_posted)->toBe(Invoice::POSTED_DRAFT);

    // publish
    $response = $this->actingAs($user)->get(route('invoices.publish', ['invoice' => $invoice->getKey()]));

    // assert
    expect($invoice->is_posted)->toBe(Invoice::POSTED_DRAFT);
    $response->assertRedirectToRoute('invoices.index');
})
    ->with([
        User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
        User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
        User::ROLE_BENDAHARA => [fn () => $this->bendahara],
        User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    ]);
