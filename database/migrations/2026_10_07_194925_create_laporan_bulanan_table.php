<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_bulanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan')->unique();
            $table->date('periode')->unique();           // selalu tanggal 1 tiap bulan
            $table->enum('status', ['Draft', 'Final'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('total_pemasukan')->default(0);
            $table->unsignedBigInteger('total_pengeluaran')->default(0);
            $table->bigInteger('laba_bersih')->default(0);
            $table->unsignedInteger('jumlah_pemasukan')->default(0);
            $table->unsignedInteger('jumlah_pengeluaran')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_bulanan');
    }
};