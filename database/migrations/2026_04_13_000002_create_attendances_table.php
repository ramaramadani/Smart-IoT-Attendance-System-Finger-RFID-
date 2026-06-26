<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('id_karyawan')->constrained('karyawan', 'id_Karyawan')->cascadeOnDelete();
            $table->date('Tanggal');
            $table->time('Jam_masuk')->nullable();
            $table->time('Jam_keluar')->nullable();
            $table->integer('Durasi')->default(0);
            $table->string('Status')->default('hadir');
            $table->text('Keterangan')->nullable();
            $table->timestamp('cread_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
