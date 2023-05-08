<?php

use App\Models\Invoice;
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
        Schema::table('invoices', function (Blueprint $table) {
            $table->after('note', function (Blueprint $table) {
                $table->string('payment_status', 30)->nullable()->default(Invoice::STATUS_PENDING);
                $table->string('is_posted')->default(Invoice::POSTED_DRAFT)->nullable();
                $table->decimal('total_amount', 15, 2)->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'is_posted', 'total_amount']);
        });
    }
};
