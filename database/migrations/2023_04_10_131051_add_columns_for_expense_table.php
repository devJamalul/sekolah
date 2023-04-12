<?php

use App\Models\User;
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
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'request_by')->nullable()->after('is_sempoa_processed');
            $table->foreignIdFor(User::class, 'approval_by')->nullable()->after('request_by');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
           $table->dropColumn([
            'request_by',
            'approval_by'
        ]);
        });
    }
};
