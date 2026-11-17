<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {

            $table->id();

            $table->string('nama');

            // Karena Azzahra Salon & Spa Muslimah khusus perempuan
            $table->enum('jk', ['Perempuan'])
                  ->default('Perempuan');

            $table->date('tgl_lahir');

            $table->string('no_telepon', 20);

            $table->string('email')
                  ->nullable();

            $table->text('alamat');

            // Menyimpan nama file foto
            $table->string('foto')
                  ->nullable();

            // Status member pelanggan
            $table->enum('member', ['Ya', 'Tidak'])
                  ->default('Tidak');

            // Jumlah pelanggan sudah datang ke salon
            $table->unsignedInteger('jumlah_kedatangan')
                  ->default(0);

            // Tanggal terakhir pelanggan datang
            $table->date('terakhir_datang')
                  ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};