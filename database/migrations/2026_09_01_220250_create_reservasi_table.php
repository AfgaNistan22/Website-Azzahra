<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {

            $table->id();

            // Kode reservasi
            $table->string('kode_reservasi')
                ->unique();

            // Relasi Pelanggan
            $table->foreignId('pelanggan_id')
                ->constrained('pelanggan')
                ->cascadeOnDelete();

            // Relasi Pegawai / Terapis
            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->time('jam_mulai');

            $table->time('jam_selesai');

            $table->enum('status', [
                'Menunggu',
                'Dikonfirmasi',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu');

            $table->text('catatan')->nullable();

            $table->timestamps();

            // Index untuk mempercepat pengecekan jadwal
            $table->index([
                'tanggal',
                'pegawai_id',
                'jam_mulai',
                'jam_selesai'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};