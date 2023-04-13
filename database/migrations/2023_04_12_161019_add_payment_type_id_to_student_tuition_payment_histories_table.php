<?php

use App\Models\PaymentType;
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
        Schema::table('student_tuition_payment_histories', function (Blueprint $table) {
            $table->after('price', function (Blueprint $table) {
                $table->foreignIdFor(PaymentType::class)->nullable()->constrained()->cascadeOnDelete();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_tuition_payment_histories', function (Blueprint $table) {
            $table->dropForeignIdFor(PaymentType::class);
            $table->dropColumn('payment_type_id');
        });
    }
};
