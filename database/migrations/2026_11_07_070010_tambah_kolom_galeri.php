<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyesuaikan tabel `galeri` yang SUDAH ADA: hanya menambah kolom yang belum ada,
 * data lama tidak disentuh. Bila tabelnya belum ada sama sekali, tabel dibuat baru.
 * Aman dijalankan berulang.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('galeri')) {
            Schema::create('galeri', function (Blueprint $table) {
                $table->id();
                $table->string('judul')->nullable();
                $table->string('kategori', 60)->nullable();
                $table->string('foto');
                $table->unsignedInteger('urutan')->default(0);
                $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
                $table->timestamps();
            });

            return;
        }

        Schema::table('galeri', function (Blueprint $table) {
            if (! Schema::hasColumn('galeri', 'judul')) {
                $table->string('judul')->nullable();
            }
            if (! Schema::hasColumn('galeri', 'kategori')) {
                $table->string('kategori', 60)->nullable();
            }
            if (! Schema::hasColumn('galeri', 'foto')) {
                $table->string('foto')->nullable();
            }
            if (! Schema::hasColumn('galeri', 'urutan')) {
                $table->unsignedInteger('urutan')->default(0);
            }
            if (! Schema::hasColumn('galeri', 'status')) {
                // baris lama otomatis berstatus Aktif, jadi langsung tampil di halaman publik
                $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            }
            if (! Schema::hasColumn('galeri', 'created_at') && ! Schema::hasColumn('galeri', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        // sengaja kosong: tabel lama milik aplikasi, jangan dihapus
    }
};