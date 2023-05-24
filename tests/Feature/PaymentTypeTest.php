<?php

use App\Models\AcademicYear;
use App\Models\PaymentType;
use App\Models\School;
use App\Models\TuitionType;
use App\Models\User;
use App\Models\Wallet;

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
    User::ROLE_BENDAHARA => [fn () => $this->bendahara]
]);

dataset('staff_only_read', [
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

dataset('staff_cannot_crud', [
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],

]);
/**
 * END DATASET
 */


/**
 * OUTSIDE CRUD
 */
it('forbid guest to view payment Type page', function () {
    $response = $this->get(route('payment-type.index'));

    // assert
    $response->assertRedirectToRoute('login');
    $this->assertGuest();
});


/**
 * CREATE RENDER PAGE
 */

test('C R E A T E', function () {
    expect(true)->toBeTrue();
});

it('can render create page', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.create'));

    $response->assertOk();
})->with('staff_can_crud');



/**
 * CREATE VALIDATION
 */

it('requires wallet_id and name on create', function (User $user) {
    $this->actingAs($user)
        ->post(route('payment-type.store'), [
            'school_id' => '',
            'name' => '',
            'wallet_id' => ''
        ])
        ->assertInvalid(['school_id', 'name', 'wallet_id']);
})->with('staff_can_crud');

/**
 * CREATE
 */
it('can create new payment type', function (User $user) {
    $wallet = Wallet::factory()->create(['school_id' => session('school_id')]);
    $name = fake()->word();
    $data = [
        'school_id' => session('school_id'),
        'name' => $name,
        'wallet_id' => $wallet->getKey()
    ];

    $this->actingAs($user)
        ->post(route('payment-type.store'), $data)->assertRedirect(route('payment-type.index'));

    $this->assertDatabaseHas('payment_types', $data);
})->with('staff_can_crud');


/**
 * Read
 *
 */
test('R E A D', function () {
    expect(true)->toBeTrue();
});

it('can render payment Type index page', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.index'));

    $response->assertOk();
})->with('staff_only_read');


/**
 * UPDATE RENDER
 */
test('U P D A T E', function () {
    expect(true)->toBeTrue();
});

it('can render edit page', function (User $user) {
    $paymentType = PaymentType::factory()->create(['school_id' => session('school_id')]);
    $this->actingAs($user)
        ->get(route('payment-type.edit', ['payment_type' => $paymentType->id]))->assertOk();
})->with('staff_can_crud');

/**
 * UPDATE VALIDATION
 */

it('requires school_id, wallet_id and name on update', function (User $user) {
    $paymentType = PaymentType::factory()->create(['school_id' => session('school_id')]);
    $this->actingAs($user)
        ->put(route('payment-type.update', ['payment_type' => $paymentType->id]), [
            'school_id' => '',
            'name' => '',
            'wallet_id' => ''
        ])
        ->assertInvalid(['school_id', 'name', 'wallet_id']);
})->with('staff_can_crud');


/**
 * UPDATE
 */

it('can update data ', function (User $user) {
    $paymentType = PaymentType::factory()->create(['school_id' => session('school_id')]);
    $old_wallet = $paymentType->wallet_id;
    $old_name = $paymentType->name;
    $new_name = fake()->word();
    $wallet = Wallet::factory()->create(['school_id' => session('school_id')]);
    $new_wallet = $wallet->getKey();

    $this->actingAs($user)
        ->put(route('payment-type.update', ['payment_type' => $paymentType->id]), [
            'school_id' => $paymentType->school_id,
            'name' => $new_name,
            'wallet_id' => $new_wallet
        ])
        ->assertRedirect(route('payment-type.index'));

    $paymentType->refresh();
    expect($paymentType->name)->toBe($new_name);
    expect($paymentType->name)->not()->toBe($old_name);
    expect($paymentType->wallet_id)->toBe($new_wallet);
    expect($paymentType->wallet_id)->not()->toBe($old_wallet);
})->with('staff_can_crud');

/**
 * DELETE
 */
test('D E L E T E', function () {
    expect(true)->toBeTrue();
});

it('can delete data ', function (User $user) {
    $paymentType = PaymentType::factory()->create(['school_id' => session('school_id')]);
    $this->actingAs($user)
        ->delete(route('payment-type.destroy', ['payment_type' => $paymentType->id]))
        ->assertOk();
})->with('staff_can_crud');


/**
 * NEGATIVE CRUD
 */
test('N E G A T I V E', function () {
    expect(true)->toBeTrue();
});

it("can't render payment Type create page", function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.create'));

    $response->assertNotFound();
})->with('staff_cannot_crud');


it("can't render payment Type Edit page", function (User $user) {
    $paymentType = PaymentType::factory()->create(['school_id' => session('school_id')]);
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.edit', $paymentType->getKey()));
    $response->assertNotFound();
})->with('staff_cannot_crud');

it("can't render payment Type store ", function (User $user) {
    $this->actingAs($user)
        ->post(route('payment-type.store'), [
            'school_id' => session('school_id'),
            'name' => fake()->name(),
        ])->assertNotFound();
})->with('staff_cannot_crud');

it("can't render payment Type update  page", function (User $user) {
    $paymentType = PaymentType::factory()->create(['school_id' => session('school_id')]);
    $name = $this->faker()->name();
    $this->actingAs($user)
        ->put(route('payment-type.update', $paymentType->getKey()), [
            'school_id' => $paymentType->school_id,
            'name' => $name,
        ])->assertNotFound();
})->with('staff_cannot_crud');

it("can't render payment Type delete data ", function (User $user) {
    $paymentType = PaymentType::factory()->create(['school_id' => session('school_id')]);
    $this->actingAs($user)
        ->delete(route('payment-type.destroy', ['payment_type' => $paymentType->id]))
        ->assertNotFound();
})->with('staff_cannot_crud');
