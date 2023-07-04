<?php

use App\Models\School;
use App\Models\SempoaConfiguration;
use App\Models\Wallet;
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
        Schema::create('sempoa_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('status')->nullable();
            $table->string('token')->nullable();
            $table->string('tuition_debit_account')->nullable();
            $table->string('tuition_credit_account')->nullable();
            $table->string('expense_debit_account')->nullable();
            $table->string('expense_credit_account')->nullable();
            $table->string('invoice_debit_account')->nullable();
            $table->string('invoice_credit_account')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sempoa_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Wallet::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('account')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sempoa_configurations');
        Schema::dropIfExists('sempoa_wallets');
    }
};
