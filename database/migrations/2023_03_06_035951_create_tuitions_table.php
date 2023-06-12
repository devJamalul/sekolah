<?php

use App\Models\Grade;
use App\Models\School;
use App\Models\TuitionType;
use App\Models\AcademicYear;
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
        Schema::create('tuitions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(TuitionType::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(AcademicYear::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Grade::class)->nullable()->constrained()->nullOnDelete();
            $table->double('price')->nullable();
            $table->enum('status', ["approved", "pending", "rejected"])->nullable();
            $table->text('reject_reason')->nullable();
            $table->foreignIdFor(User::class, 'request_by')->nullable();
            $table->foreignIdFor(User::class, 'approval_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignIdFor(User::class, 'rejected_by')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuitions');
    }
};
