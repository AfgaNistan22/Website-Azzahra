<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo', function (Blueprint $table) {
            // buang foreign key lama dulu
            $table->dropForeign(['perawatan_id']);

            // NULL = berlaku untuk semua perawatan
            $table->unsignedBigInteger('perawatan_id')->nullable()->change();

            $table->foreign('perawatan_id')
                ->references('id')
                ->on('perawatan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('promo', function (Blueprint $table) {
            $table->dropForeign(['perawatan_id']);
            $table->unsignedBigInteger('perawatan_id')->nullable(false)->change();
            $table->foreign('perawatan_id')
                ->references('id')
                ->on('perawatan')
                ->cascadeOnDelete();
        });
    }
};