<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('testimoni')) {
            return;
        }

        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();

            // satu akun = satu ulasan (bisa diperbarui), ikut terhapus bila akunnya dihapus
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');   // 1 sampai 5
            $table->text('komentar');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimoni');
    }
};