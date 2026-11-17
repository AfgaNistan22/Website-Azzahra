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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservasi_id')
                ->constrained('reservasi')
                ->cascadeOnDelete();

            $table->date('tanggal_bayar')
                ->nullable();

            $table->enum('metode_pembayaran', [
                'Cash',
                'Transfer',
                'E-Wallet'
            ])->nullable();

            $table->bigInteger('jumlah');

            $table->enum('status', [
                'Menunggu',
                'Lunas',
                'Dibatalkan'
            ])->default('Menunggu');

            $table->text('catatan')
                ->nullable();

            $table->timestamps();

            $table->unique('reservasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};