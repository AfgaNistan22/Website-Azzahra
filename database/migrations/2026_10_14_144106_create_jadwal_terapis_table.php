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
        Schema::create('jadwal_terapis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_terapis');
            $table->string('spesialisasi')->nullable();
            $table->string('foto')->nullable();
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['Tersedia', 'Libur'])
                ->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_terapis');
    }
};
