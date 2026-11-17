<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();

            $table->string('nama');

            $table->string('no_telepon', 20)
                ->nullable();

            $table->string('email')
                ->nullable();

            $table->text('alamat')
                ->nullable();

            $table->string('foto')
                ->nullable();

            $table->enum('status', [
                'Aktif',
                'Tidak Aktif'
            ])->default('Aktif');

            $table->timestamps();
        });
    }

    /**
     * Balikkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};