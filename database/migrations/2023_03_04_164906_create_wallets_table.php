<?php

use App\Models\School;
use App\Models\User;
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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->nullable();
            $table->string('name')->nullable();
            $table->double('init_value')->nullable();
            $table->double('last_balance')->nullable();
            $table->double('withholding_balance')->nullable();
            $table->boolean('danabos')->default(false)->comment('Flag untuk Dana Bos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
