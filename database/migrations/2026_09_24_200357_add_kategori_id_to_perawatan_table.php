<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('perawatan', 'kategori_id')) {

            Schema::table('perawatan', function (Blueprint $table) {

                $table->foreignId('kategori_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('kategori_perawatan')
                    ->nullOnDelete();

                $table->index('kategori_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('perawatan', 'kategori_id')) {

            Schema::table('perawatan', function (Blueprint $table) {

                $table->dropForeign(['kategori_id']);

                $table->dropIndex(['kategori_id']);

                $table->dropColumn('kategori_id');
            });
        }
    }
};