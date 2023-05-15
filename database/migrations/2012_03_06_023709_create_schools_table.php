<?php

use App\Models\School;
use App\Models\Staff;
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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->enum('grade', ["TK", "SD", "SMP", "SMA", "SMK"])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('school_name');
            $table->string('foundation_head_name')->nullable();
            $table->string('foundation_head_tlpn')->nullable();
            $table->foreignIdFor(Staff::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(School::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
