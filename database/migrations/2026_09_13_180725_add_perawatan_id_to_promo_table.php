<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo', function (Blueprint $table) {
            $table->foreignId('perawatan_id')
                ->nullable()
                ->after('id')
                ->constrained('perawatan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('promo', function (Blueprint $table) {
            $table->dropForeign(['perawatan_id']);
            $table->dropColumn('perawatan_id');
        });
    }
};