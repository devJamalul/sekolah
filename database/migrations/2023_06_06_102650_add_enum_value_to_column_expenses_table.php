<?php

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
        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('status', ["approved", "pending", "rejected", "outgoing", "done"])->nullable()->change();
            $table->string('file_photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('status', ["approved", "pending", "rejected"])->nullable()->change();
            $table->string('file_photo')->nullable();
        });
    }
};
