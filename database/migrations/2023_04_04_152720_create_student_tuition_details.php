<?php

use App\Models\Student;
use App\Models\StudentTuition;
use App\Models\Tuition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_tuition_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tuition::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Student::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(StudentTuition::class)->nullable()->constrained()->nullOnDelete();
            $table->double('price')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_tuition_details');
    }
};
