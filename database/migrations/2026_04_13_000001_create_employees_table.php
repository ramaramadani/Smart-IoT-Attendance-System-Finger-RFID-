<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_Karyawan');
            $table->string('Nip')->unique();
            $table->string('Nama');
            $table->string('Jenis_Kelamin');
            $table->string('Jabatan');
            $table->foreignId('id_departemen')->constrained('departemen', 'id_departemen')->cascadeOnDelete();
            $table->string('id_fingerprint')->nullable();
            $table->string('id_RFID')->nullable();
            $table->date('Tanggal_bergabung');
            $table->string('Status')->default('aktif');
            $table->timestamp('cread_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
