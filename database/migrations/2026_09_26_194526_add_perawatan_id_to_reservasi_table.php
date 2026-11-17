<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {

            $table->foreignId('perawatan_id')
                ->after('pelanggan_id')
                ->constrained('perawatan')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {

            $table->dropForeign([
                'perawatan_id'
            ]);

            $table->dropColumn('perawatan_id');

        });
    }
};