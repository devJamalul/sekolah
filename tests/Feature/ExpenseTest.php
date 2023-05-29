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
it('forbid guest to view Expense page', function () {
    $this
        ->get(route('expense.index'))
        ->assertNotFound();
})->todo();

// Render Create
it('can render Expense create page', function (User $user) {
    $response = $this
        ->actingAs($user)
        ->get(route('expense.create'));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can create new Expense', function (User $user) {
    $school = School::factory()->create();
    $randomNumber = rand(1, 2000);;
    $expenseNumber = 'Exp/' . date('Y') . '/' . str_pad($randomNumber == 0 ? $randomNumber += 1 : $randomNumber += 1, 4, '0', STR_PAD_LEFT);
    $expenseDate = date('Y-m-d');
    $requestApprovedBy = User::factory()->create();

    $this->actingAs($user)
        ->post(route('expense.store'), [
            'school_id' => $school->getKey(),
            'expense_number' => $expenseNumber,
            'expense_date' => $expenseDate,
            'requested_by' => $requestApprovedBy->getKey(),
            'approved_by' => $requestApprovedBy->getKey(),
        ]);

    $this->assertDatabaseHas('expenses', [
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
    ]);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
])->todo();

// Render Index
it('can render Expense index page', function (User $user) {
    $response = $this->actingAs($user)
                    ->get(route('expense.index'));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
])->todo();

// Render Update
it('can render Expense edit page', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $randomNumber = rand(1, 2000);;
    $expenseNumber = 'Exp/' . date('Y') . '/' . str_pad($randomNumber == 0 ? $randomNumber += 1 : $randomNumber += 1, 4, '0', STR_PAD_LEFT);
    $expenseDate = date('Y-m-d');
    $requestApprovedBy = User::factory()->create();

    $expense = $school->expenses()->create([
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->assertDatabaseHas('expenses', [
        'id' => $expense->getKey(),
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $response = $this->actingAs($user)
                    ->get(route('expense.edit', $expense->getKey()));

    $response->assertOk();
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);

it('can edit Expense', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $randomNumber = rand(1, 2000);;
    $expenseNumber = 'Exp/' . date('Y') . '/' . str_pad($randomNumber == 0 ? $randomNumber += 1 : $randomNumber += 1, 4, '0', STR_PAD_LEFT);
    $expenseDate = date('Y-m-d');
    $requestApprovedBy = User::factory()->create();

    $expense = $school->expenses()->create([
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->actingAs($user)
            ->put(route('expense.update', $expense->getKey()),[
                'school_id' => $expense->school_id,
                'expense_number' => $expense->expense_number,
                'expense_date' => $expense->expense_date,
                'requested_by' => $expense->request_by,
                'approved_by' => $expense->approval_by,
            ])
        ->assertRedirect(route('expense.index'));

    $this->assertDatabaseHas('expenses', [
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
])->todo();

// Render Delete
it('can delete expense', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $randomNumber = rand(1, 2000);;
    $expenseNumber = 'Exp/' . date('Y') . '/' . str_pad($randomNumber == 0 ? $randomNumber += 1 : $randomNumber += 1, 4, '0', STR_PAD_LEFT);
    $expenseDate = date('Y-m-d');
    $requestApprovedBy = User::factory()->create();

    $expense = $school->expenses()->create([
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->actingAs($user)
        ->delete(route('expense.destroy', $expense->getKey()))
        ->assertStatus(200);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
]);


// Negation CRUD
it('can not render Expense create page', function (User $user) {
    $response = $this->actingAs($user)
                    ->get(route('expense.create'));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
])->todo();

it('can not create new expense with Invalid requires', function (User $user) {
    $this->actingAs($user)
        ->post(route('expense.store'))
        ->assertSessionHasErrors(['expense_number', 'expense_date', 'requested_by', 'approved_by']);

})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
])->todo();

it('can not render Expense edit page', function (User $user) {
    $school = School::factory()->create();
    $randomNumber = rand(1, 2000);;
    $expenseNumber = 'Exp/' . date('Y') . '/' . str_pad($randomNumber == 0 ? $randomNumber += 1 : $randomNumber += 1, 4, '0', STR_PAD_LEFT);
    $expenseDate = date('Y-m-d');
    $requestApprovedBy = User::factory()->create();


    $expense = $school->expenses()->create([
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $response = $this->actingAs($user)
                    ->get(route('expense.edit', $expense->getKey()));

    $response->assertNotFound();
})->with([
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
])->todo();

it('can not edit Expense with Invalid requires', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $randomNumber = rand(1, 2000);;
    $expenseNumber = 'Exp/' . date('Y') . '/' . str_pad($randomNumber == 0 ? $randomNumber += 1 : $randomNumber += 1, 4, '0', STR_PAD_LEFT);
    $expenseDate = date('Y-m-d');
    $requestApprovedBy = User::factory()->create();

    $expense = $school->expenses()->create([
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);

    $this->actingAs($user)
        ->put(route('expense.update', $expense->getKey()), [
            'expense_date' => '',
            'requested_by' => '',
            'approved_by' => '',
        ])
        ->assertSessionHasErrors(['expense_date', 'requested_by', 'approved_by']);
})->with([
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
])->todo();

it('can not delete Tuition', function (User $user) {
    $school = School::factory()->create();
    session(['school_id' => $school->getKey()]);
    $randomNumber = rand(1, 2000);;
    $expenseNumber = 'Exp/' . date('Y') . '/' . str_pad($randomNumber == 0 ? $randomNumber += 1 : $randomNumber += 1, 4, '0', STR_PAD_LEFT);
    $expenseDate = date('Y-m-d');
    $requestApprovedBy = User::factory()->create();

    $expense = $school->expenses()->create([
        'school_id' => $school->getKey(),
        'expense_number' => $expenseNumber,
        'expense_date' => $expenseDate,
        'request_by' => $requestApprovedBy->getKey(),
        'approval_by' => $requestApprovedBy->getKey(),
    ]);


    $response = $this->actingAs($user)
                    ->delete(route('expense.destroy', $expense->getKey()));

    $response->assertNotFound();

})->with([
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
])->todo();
