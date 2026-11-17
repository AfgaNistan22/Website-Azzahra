<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gaji_id')
                ->nullable()
                ->unique()
                ->constrained('gaji')
                ->nullOnDelete();

            $table->foreignId('pajak_id')
                ->nullable()
                ->unique()
                ->constrained('pajak')
                ->nullOnDelete();

            $table->string('kode_pengeluaran')->unique();

            $table->enum('sumber_jenis', ['Manual', 'Gaji', 'Pajak'])
                ->default('Manual');

            $table->date('tanggal');
            $table->string('kategori', 100);
            $table->string('sumber')->nullable();
            $table->bigInteger('nominal')->default(0);

            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'E-Wallet'])
                ->nullable();

            $table->enum('status', ['Diterima', 'Dibatalkan'])
                ->default('Diterima');

            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tanggal', 'sumber_jenis']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
