<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_master', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan')->unique();
            $table->string('kategori', 50)->index();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['Draft', 'Final'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('jumlah_data')->default(0);
            $table->string('kolom_nominal')->nullable();      // label kolom nominal yang dijumlahkan
            $table->bigInteger('total_nominal')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_master');
    }
};