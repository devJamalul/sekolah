<?php

use App\Models\AcademicYear;
use App\Models\PaymentType;
use App\Models\School;
use App\Models\TuitionType;
use App\Models\User;

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
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
]);

dataset('staff_cannot_crud', [
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
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
    $this
        ->get(route('payment-type.index'))
        ->assertNotFound();
});


/**
 * CREATE RENDER PAGE
 */
it('can render payment Type create page as ', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.create'));

    $response->assertOk();
})->with('staff_can_crud');



/**
 * CREATE VALIDATION
 */

it('can render payment Type create invalid required school_id and name as ', function (User $user) {
    $this->actingAs($user)
        ->post(route('payment-type.store'), [
            'school_id' => '',
            'name' => '',
        ])
        ->assertInvalid([
            'school_id' => 'required',
            'name' => 'required'
        ]);
})->with('staff_can_crud');

/**
 * CREATE
 */
it('can render payment Type create data post  as ', function (User $user) {
    $school = School::factory()->create();
    $name = fake()->randomElement(['manual', 'cash', 'qris', 'bca', 'bni']);
    $this->actingAs($user)
        ->post(route('payment-type.store'), [
            'school_id' => $school->id,
            'name' => $name,
        ])->assertRedirect(route('payment-type.index'));
    $this->assertDatabaseHas('payment_types', [
        'school_id' => $school->id,
        'name' => $name
    ]);
})->with('staff_can_crud');


/**
 * Read
 *
 */

it('can render payment Type index Datatable page as ', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('datatable.payment-type'));

    $response->assertOk();
})->with('staff_only_read');

it('can render payment Type page index page as ', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.index'));

    $response->assertOk();
})->with('staff_only_read');



/**
 * UPDATE RENDER
 */

it('can render page edit payment Type  as ', function (User $user) {
    $paymentType = PaymentType::factory()->create();
    session(['school_id' => $paymentType->school_id]);
    $this->actingAs($user)
        ->get(route('payment-type.edit', ['payment_type' => $paymentType->id]))->assertOk();
})->with('staff_can_crud');

/**
 * UPDATE VALIDATION
 */

it('can render payment Type update invalid required school_id and name as ', function (User $user) {
    $paymentType = PaymentType::factory()->create();
    session(['school_id' => $paymentType->school_id]);
    $this->actingAs($user)
        ->put(route('payment-type.update', ['payment_type' => $paymentType->id]), [
            'school_id' => '',
            'name' => '',
        ])
        ->assertInvalid([
            'school_id' => 'required',
            'name' => 'required'
        ]);
})->with('staff_can_crud');


/**
 * UPDATE
 */

it('can render payment Type update data  as ', function (User $user) {
    $paymentType = PaymentType::factory()->create();
    session(['school_id' => $paymentType->school_id]);
    $year = fake()->year('-10 years');
    $yearAcademy = $year . "-" . $year + 1;
    $this->actingAs($user)
        ->put(route('payment-type.update', ['payment_type' => $paymentType->id]), [
            'school_id' => $paymentType->school_id,
            'name' => $yearAcademy,
        ])
        ->assertRedirect(route('payment-type.index'));
    $this->assertDatabaseHas('payment_types', ['name' => $yearAcademy]);
})->with('staff_can_crud');

/**
 * DELETE
 */
it('can render payment Type delete data  as ', function (User $user) {
    $paymentType = PaymentType::factory()->create();
    session(['school_id' => $paymentType->school_id]);
    $this->actingAs($user)
        ->delete(route('payment-type.destroy', ['payment_type' => $paymentType->id]))
        ->assertOk();
})->with('staff_can_crud');


/**
 * NEGATIVE CRUD
 */

it("can't render payment Type create page as ", function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.create'));

    $response->assertNotFound();
})->with('staff_cannot_crud');


it("can't render payment Type Edit page as ", function (User $user) {
    $paymentType = PaymentType::factory()->create();
    $response = $this
        ->actingAs($user)
        ->get(route('payment-type.edit', $paymentType->getKey()));
    $response->assertNotFound();
})->with('staff_cannot_crud');

it("can't render payment Type store  as ", function (User $user) {
    $school = School::factory()->create();
    $this->actingAs($user)
        ->post(route('payment-type.store'), [
            'school_id' => $school->id,
            'name' => fake()->name(),
        ])->assertNotFound();
})->with('staff_cannot_crud');

it("can't render payment Type update  page as ", function (User $user) {
    $paymentType = PaymentType::factory()->create();
    $name = $this->faker()->name();
    $this->actingAs($user)
        ->put(route('payment-type.update', $paymentType->getKey()), [
            'school_id' => $paymentType->school_id,
            'name' => $name,
        ])->assertNotFound();
})->with('staff_cannot_crud');

it("can't render payment Type delete data  as ", function (User $user) {
    $paymentType = PaymentType::factory()->create();
    $this->actingAs($user)
        ->delete(route('payment-type.destroy', ['payment_type' => $paymentType->id]))
        ->assertNotFound();
})->with('staff_cannot_crud');
