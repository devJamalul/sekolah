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
        // by Jamal
        Schema::create('expense_wallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('expense_id')->unsigned()->nullable();
            $table->bigInteger('wallet_id')->unsigned()->nullable();
            $table->double('amount')->nullable();
            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('expense_id')
                ->references('id')
                ->on('expenses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_wallets');
    }
};
