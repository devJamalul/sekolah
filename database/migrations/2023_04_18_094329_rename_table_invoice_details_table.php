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
        Schema::rename('table_invoice_details', 'invoice_details');
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->foreignIdFor(Invoice::class)->after('price')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->text('note')->nullable()->after('due_date');
            $table->boolean('is_sempoa_processed')->nullable()->default(false)->renameTo('sempoa_processed')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('invoice_details', 'table_invoice_details');
        Schema::table('table_invoice_details', function (Blueprint $table) {
            $table->dropForeignIdFor(Invoice::class);
            $table->dropColumn('invoice_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->boolean('sempoa_processed')->nullable()->default(false)->renameTo('is_sempoa_processed')->change();
        });
    }
};
