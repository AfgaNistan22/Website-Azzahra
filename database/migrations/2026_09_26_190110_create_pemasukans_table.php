<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemasukan')->unique();
            $table->foreignId('pembayaran_id')->nullable()->unique()->constrained('pembayaran')->nullOnDelete();
            $table->enum('sumber_jenis', ['Reservasi', 'Manual'])->default('Manual');
            $table->date('tanggal');
            $table->string('kategori', 100);
            $table->string('sumber')->nullable();
            $table->bigInteger('nominal')->default(0);
            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'E-Wallet'])->nullable();
            $table->enum('status', ['Diterima', 'Dibatalkan'])->default('Diterima');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tanggal', 'status']);
            $table->index(['sumber_jenis', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};
