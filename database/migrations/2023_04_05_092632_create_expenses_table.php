<?php

use App\Models\School;
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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class, 'school_id')->nullable();
            $table->string('expense_number')->nullable();
            $table->date('expense_date')->nullable();
            $table->double('price')->default(0);
            $table->text('note')->nullable();
            $table->string('status')->nullable();
            $table->string('file_photo')->nullable();
            $table->text('reject_reason')->nullable();
            $table->date('expense_outgoing_date')->nullable();
            $table->boolean('is_sempoa_processed')->nullable()->default(false);
            $table->foreignIdFor(User::class, 'request_by')->nullable();
            $table->foreignIdFor(User::class, 'approval_by')->nullable();
            $table->date('approval_at')->nullable();
            $table->foreignIdFor(User::class, 'rejected_by')->nullable();
            $table->date('rejected_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
