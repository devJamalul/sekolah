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
        Schema::table('students', function (Blueprint $table) {
            $table->string('father_email')->after('father_address')->nullable();
            $table->string('mother_email')->after('mother_address')->nullable();
            $table->string('guardian_email')->after('guardian_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('father_email');
            $table->dropColumn('mother_email');
            $table->dropColumn('guardian_email');
        });
    }
};
