<?php

use App\Models\Invoice;
use App\Models\School;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class, 'school_id')->nullable();
            $table->string('invoice_number');
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('note')->nullable();
            $table->string('payment_status', 30)->nullable()->default(Invoice::STATUS_PENDING);
            $table->string('is_posted')->default(Invoice::POSTED_DRAFT)->nullable();
            $table->boolean('is_original')->default(true);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->boolean('sempoa_processed')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
