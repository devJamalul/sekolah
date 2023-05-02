<?php

use App\Models\User;
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
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('owner_id');
            $table->foreignIdFor(Staff::class)->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('foundation_head_name')->after('school_name')->nullable();
            $table->string('foundation_head_tlpn')->after('school_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeignIdFor(Staff::class);
            $table->dropColumn(['foundation_head_tlpn', 'foundation_head_name', 'staff_id']);
            $table->foreignIdFor(User::class, 'owner_id')->nullable()->nullOnDelete();;
        });
    }
};
