<?php

use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(School::class)->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->comment("Student Email for receiving information");
            $table->string('status')->nullable()->default('active');
            $table->string('gender', 1)->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('religion', 10)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('family_card_number', 25)->nullable();
            $table->string('nisn', 10)->nullable();
            $table->string('nis')->nullable();
            $table->string('file_family_card')->nullable();
            $table->string('file_birth_certificate')->nullable();
            $table->string('file_photo')->nullable();
            $table->string('father_name')->nullable();
            $table->text('father_address')->nullable();
            $table->text('father_email')->nullable();
            $table->string('father_phone_number')->nullable();
            $table->string('mother_name')->nullable();
            $table->text('mother_address')->nullable();
            $table->text('mother_email')->nullable();
            $table->string('mother_phone_number')->nullable();
            $table->string('guardian_name')->nullable();
            $table->text('guardian_address')->nullable();
            $table->text('guardian_email')->nullable();
            $table->string('guardian_phone_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
