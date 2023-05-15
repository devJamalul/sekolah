<?php

use App\Models\PaymentType;
use App\Models\StudentTuition;
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
        Schema::create('student_tuition_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StudentTuition::class)->nullable()->constrained()->nullOnDelete();
            $table->double('price')->nullable();
            $table->foreignIdFor(PaymentType::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_tuition_payment_histories');
    }
};
