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
        Schema::table('staff', function (Blueprint $table) {
            $table->after('name', function (Blueprint $table) {
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
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'gender', 'address', 'dob', 'religion', 'phone_number', 'nik', 'nidn', 'nip', 'family_card_number', 'file_photo', 'file_birth_certificate', 'file_family_card',
            ]);
        });
    }
};
