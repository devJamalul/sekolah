<?php

use App\Models\User;
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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('gender', 10)->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('religion', 10)->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('nik', 16)->nullable()->comment('Nomor induk kependudukan');
            $table->string('nidn', 20)->nullable()->comment('Nomor induk dosen nasional');
            $table->string('nip', 20)->nullable()->format('Nomor induk pegawai');
            $table->string('family_card_number', 25)->nullable()->comment('Nomor kartu keluarga');
            $table->string('file_photo')->nullable();
            $table->string('file_birth_certificate')->nullable();
            $table->string('file_family_card')->nullable()->comment('Kartu keluarga');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->foreignIdFor(Staff::class)->nullable()->after('foundation_head_name')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');

        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeignIdFor(Staff::class);
            $table->dropColumn('staff_id');
        });
    }
};
