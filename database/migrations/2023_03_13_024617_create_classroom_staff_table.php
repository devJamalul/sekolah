<?php

use App\Models\Classroom;
use App\Models\School;
use App\Models\Staff;
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
        Schema::create('classroom_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Classroom::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Staff::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_staff');
    }
};
