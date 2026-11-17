<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gaji', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('bulan');

            $table->unsignedSmallInteger('tahun');

            $table->bigInteger('gaji_pokok')->default(0);

            $table->bigInteger('tunjangan')->default(0);

            $table->bigInteger('potongan')->default(0);

            $table->bigInteger('total_gaji')->default(0);

            $table->enum('status', [
                'Belum Dibayar',
                'Dibayar'
            ])->default('Belum Dibayar');

            $table->date('tanggal_bayar')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();

            /*
             * Satu pegawai hanya boleh memiliki
             * satu data gaji pada bulan dan tahun yang sama.
             */
            $table->unique([
                'pegawai_id',
                'bulan',
                'tahun'
            ]);

            $table->index([
                'tahun',
                'bulan'
            ]);

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gaji');
    }
};