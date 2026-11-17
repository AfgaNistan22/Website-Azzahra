<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {

            // Promo yang digunakan
            $table->foreignId('promo_id')
                ->nullable()
                ->after('reservasi_id')
                ->constrained('promo')
                ->nullOnDelete();

            // Jumlah harga sebelum diskon
            $table->bigInteger('jumlah_sebelum_diskon')
                ->default(0)
                ->after('jumlah');

            // Jumlah diskon
            $table->bigInteger('jumlah_diskon')
                ->default(0)
                ->after('jumlah_sebelum_diskon');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {

            $table->dropForeign([
                'promo_id'
            ]);

            $table->dropColumn([
                'promo_id',
                'jumlah_sebelum_diskon',
                'jumlah_diskon'
            ]);
        });
    }
};