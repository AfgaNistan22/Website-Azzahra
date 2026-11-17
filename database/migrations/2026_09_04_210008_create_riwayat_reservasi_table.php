<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_reservasi', function (Blueprint $table) {

            $table->id();

            // ID reservasi asli
            $table->unsignedBigInteger('reservasi_id')->nullable();

            // ID data terkait
            $table->unsignedBigInteger('pelanggan_id')->nullable();
            $table->unsignedBigInteger('perawatan_id')->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();

            // Snapshot data agar riwayat tetap aman
            // meskipun data master berubah/dihapus
            $table->string('nama_pelanggan');
            $table->string('nama_perawatan');
            $table->string('nama_pegawai');

            // Data reservasi
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            // Status akhir
            $table->enum('status', [
                'Selesai',
                'Dibatalkan'
            ]);

            $table->text('catatan')->nullable();

            // Waktu ketika masuk riwayat
            $table->timestamp('tanggal_riwayat')->nullable();

            $table->timestamps();

            // Index untuk pencarian/filter
            $table->index('tanggal');
            $table->index('status');
            $table->index('pelanggan_id');
            $table->index('pegawai_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_reservasi');
    }
};