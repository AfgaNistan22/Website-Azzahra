
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan provider jika belum tersedia
        if (!Schema::hasColumn('users', 'provider')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('provider', 30)->nullable();
            });
        }

        // Tambahkan provider_id jika belum tersedia
        if (!Schema::hasColumn('users', 'provider_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('provider_id')->nullable();
            });
        }

        // Tambahkan avatar jika belum tersedia
        if (!Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar', 500)->nullable();
            });
        }

        // Buat kolom phone jika belum tersedia
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 20)->nullable();
            });
        }

        // Tambahkan unique provider dan provider_id
        Schema::table('users', function (Blueprint $table) {
            $table->unique(
                ['provider', 'provider_id'],
                'users_provider_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_provider_unique');
        });

        foreach (['provider', 'provider_id', 'avatar', 'phone'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
