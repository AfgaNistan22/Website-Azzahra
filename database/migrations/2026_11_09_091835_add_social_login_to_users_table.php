<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider', 30)->nullable();
            }
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar', 500)->nullable();
            }
        });

        // Akun sosial belum punya nomor telepon.
        // (Laravel 10 ke bawah: jalankan `composer require doctrine/dbal` dulu.)
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique(['provider', 'provider_id'], 'users_provider_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_provider_unique');
            $table->dropColumn(['provider', 'provider_id', 'avatar']);
        });
    }
};