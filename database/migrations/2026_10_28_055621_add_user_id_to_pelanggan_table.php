<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menautkan data pelanggan ke akun pengguna.
 *
 * - nullable : pelanggan yang dibuat admin (datang langsung) belum punya akun.
 * - unique   : satu akun hanya boleh punya satu data pelanggan. Indeks ini juga
 *              yang mencegah data ganda bila dua permintaan datang bersamaan.
 * - nullOnDelete : bila akun dihapus, riwayat pelanggan tetap ada.
 *
 * Nama tabel "pelanggan" mengikuti penamaan tabel lain di proyek ini
 * (pegawai, reservasi). Ubah bila nama tabel Anda berbeda.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pelanggan', 'user_id')) {
            Schema::table('pelanggan', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->unique()
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pelanggan', 'user_id')) {
            Schema::table('pelanggan', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};