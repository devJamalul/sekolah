<?php

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\School;
use App\Models\StudentTuition;
use App\Models\Transaction;
use App\Models\Tuition;
use App\Models\TuitionType;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->superAdmin = User::role(User::ROLE_SUPER_ADMIN)->first();
    $this->opsAdmin = User::role(User::ROLE_OPS_ADMIN)->first();
    $this->adminYayasan = User::role(User::ROLE_ADMIN_YAYASAN)->first();
    $this->adminSekolah = User::role(User::ROLE_ADMIN_SEKOLAH)->first();
    $this->bendahara = User::role(User::ROLE_BENDAHARA)->first();
    $this->tataUsaha = User::role(User::ROLE_TATA_USAHA)->first();
    $this->kepalaSekolah = User::role(User::ROLE_KEPALA_SEKOLAH)->first();
    $this->siswa = User::role(User::ROLE_SISWA)->first();
    $this->setupFaker();

    $this->denda = 25000;
});

test('siswa memiliki tagihan custom', function (User $user) {
    $school = School::find(2);
    session(['school_id' => $school->getKey()]);

    // dummy data
    $academic_year = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $tuition_type = TuitionType::factory()->create([
        'school_id' => $school->getKey(),
        'penalty_price' => $this->denda,
        'penalty_dates' => '14,28'
    ]);
    $student_tuition = StudentTuition::factory()->create([
        'school_id' => $school->getKey(),
        'student_id' => $this->siswa->getKey(),
        'tuition_type_id' => $tuition_type->getKey(),
        'price' => 50000,
        'penalty' => true
    ]);

    // Publish Tuition
    $tuition = Tuition::factory()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuition_type->getKey(),
        'academic_year_id' => $academic_year->getKey(),
        'grade_id' => $grade->getKey(),
        'period' => '2023-01-01',
        'price' => 100000
    ]);
    publishTuition($tuition);

    $trasaction = Transaction::where([
        'student_id' => $this->siswa->getKey(),
        'school_id' => $school->getKey(),
        'tuition_id' => $tuition->getKey(),
    ]);

    expect($trasaction->price)->toBe($student_tuition->price);
})->with('sempoa_staff')->skip();

test('siswa dapat mencicil dan tagihannya dibebankan ke bulan depan', function (User $user) {
    $school = School::find(2);
    session(['school_id' => $school->getKey()]);

    // dummy data
    $academic_year = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $tuition_type = TuitionType::factory()->create([
        'school_id' => $school->getKey(),
        'penalty_price' => $this->denda,
        'penalty_dates' => '14,28'
    ]);

    // Publish Tuition
    $tuition = Tuition::factory()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuition_type->getKey(),
        'academic_year_id' => $academic_year->getKey(),
        'grade_id' => $grade->getKey(),
        'period' => '2023-01-01',
        'price' => 100000
    ]);
    publishTuition($tuition);

    $tuition2 = Tuition::factory()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuition_type->getKey(),
        'academic_year_id' => $academic_year->getKey(),
        'grade_id' => $grade->getKey(),
        'period' => '2023-02-01',
        'price' => 100000
    ]);
    publishTuition($tuition2);

    // Transaksi
    $this->actingAs($user)
        ->post(route('transactions.store'), [
            'student_id' => $this->siswa->getKey(),
            'tuition_id' => $tuition->getKey(),
            'price' => 50000,
        ]);

    $trasaction = Transaction::where([
        'student_id' => $this->siswa->getKey(),
        'school_id' => $school->getKey(),
        'tuition_id' => $tuition2->getKey(),
    ]);

    expect($trasaction->price)->toBe(150000);
})->with('sempoa_staff')->skip();

test('siswa bisa kena denda', function (User $user) {
    $school = School::find(2);
    session(['school_id' => $school->getKey()]);

    // dummy data
    $academic_year = AcademicYear::factory()->create(['school_id' => $school->getKey()]);
    $grade = Grade::factory()->create(['school_id' => $school->getKey()]);
    $tuition_type = TuitionType::factory()->create([
        'school_id' => $school->getKey(),
        'penalty_price' => $this->denda,
        'penalty_dates' => '14,28'
    ]);
    StudentTuition::factory()->create([
        'school_id' => $school->getKey(),
        'student_id' => $this->siswa->getKey(),
        'tuition_type_id' => $tuition_type->getKey(),
        'penalty' => true
    ]);

    // Publish Tuition
    $tuition = Tuition::factory()->create([
        'school_id' => $school->getKey(),
        'tuition_type_id' => $tuition_type->getKey(),
        'academic_year_id' => $academic_year->getKey(),
        'grade_id' => $grade->getKey(),
        'period' => '2023-01-01',
        'price' => 100000
    ]);
    publishTuition($tuition);

    // loncat ke tanggal 15
    $this->travelTo(Carbon::parse('2023-01-15'));
    $trasaction = Transaction::where([
        'student_id' => $this->siswa->getKey(),
        'school_id' => $school->getKey(),
        'tuition_id' => $tuition->getKey(),
    ]);
    checkTransaction($this->siswa, $tuition, Carbon::parse('2023-01-15'));
    expect($trasaction->price)->toBe(125000);

    // loncat ke tanggal 1 bulan depan
    $this->travelTo(Carbon::parse('2023-02-01'));
    $trasaction = Transaction::where([
        'student_id' => $this->siswa->getKey(),
        'school_id' => $school->getKey(),
        'tuition_id' => $tuition->getKey(),
    ]);
    checkTransaction($this->siswa, $tuition, Carbon::parse('2023-02-01'));
    expect($trasaction->price)->toBe(150000);
})->with('sempoa_staff')->skip();
