<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi_perawatan', function (Blueprint $table) {

            $table->id();

            /**
             * Relasi ke reservasi
             */
            $table->foreignId('reservasi_id')
                ->constrained('reservasi')
                ->cascadeOnDelete();

            /**
             * Relasi ke perawatan
             */
            $table->foreignId('perawatan_id')
                ->constrained('perawatan')
                ->cascadeOnDelete();

            /**
             * Jumlah perawatan
             */
            $table->unsignedInteger('jumlah')
                ->default(1);

            /**
             * Snapshot harga ketika reservasi dibuat
             */
            $table->bigInteger('harga');

            /**
             * Snapshot durasi ketika reservasi dibuat
             */
            $table->integer('durasi');

            $table->timestamps();

            /**
             * Satu jenis perawatan tidak boleh
             * tercatat dua kali dalam satu reservasi.
             */
            $table->unique([
                'reservasi_id',
                'perawatan_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi_perawatan');
    }
};