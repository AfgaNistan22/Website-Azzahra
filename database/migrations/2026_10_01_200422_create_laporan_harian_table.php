<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();

            $table->string('kode_laporan', 30)->unique();

            $table->date('tanggal')->unique();

            $table->bigInteger('total_pemasukan')->default(0);

            $table->bigInteger('total_pengeluaran')->default(0);

            $table->bigInteger('laba_bersih')->default(0);

            $table->unsignedInteger('jumlah_pemasukan')->default(0);

            $table->unsignedInteger('jumlah_pengeluaran')->default(0);

            $table->enum('status', [
                'Draft',
                'Final'
            ])->default('Draft');

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index('tanggal');

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_harian');
    }
};