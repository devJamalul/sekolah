<?php

use App\Models\Grade;
use App\Models\PaymentType;
use App\Models\School;
use App\Models\Student;
use App\Models\TuitionType;
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
        Schema::create('student_tuitions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Student::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(PaymentType::class)->nullable()->constrained()->nullOnDelete();
            $table->text('note')->nullable();
            $table->date('period')->nullable();
            $table->string('bill_number')->nullable();
            $table->double('grand_total')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_sent')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_tuitions');
    }
};
