<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Pelanggan yang mendaftar sendiri lewat form registrasi hanya mengisi nama,
 * email, dan nomor telepon. Kolom profil lain (tgl_lahir, alamat, foto, dan
 * sejenisnya) tidak tersedia pada saat itu, sehingga harus boleh kosong.
 *
 * Migration ini mencari kolom di tabel pelanggan yang:
 *   - NOT NULL, dan
 *   - tidak punya nilai bawaan, dan
 *   - tidak diisi otomatis oleh PelangganAkun,
 * lalu mengubahnya menjadi NULL dengan tipe data yang tetap sama.
 *
 * Khusus MySQL / MariaDB. Aman dijalankan berulang kali.
 */
return new class extends Migration
{
    /** Kolom yang selalu diisi oleh App\Services\PelangganAkun, jadi tidak diubah. */
    private const DIISI_SERVICE = [
        'id', 'user_id', 'nama', 'email', 'no_telepon',
        'jk', 'member', 'jumlah_kedatangan',
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('pelanggan')) {
            return;
        }

        $kolom = DB::select(
            "SELECT COLUMN_NAME AS nama, COLUMN_TYPE AS tipe
               FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND IS_NULLABLE = 'NO'
                AND COLUMN_DEFAULT IS NULL
                AND EXTRA NOT LIKE '%auto_increment%'
                AND EXTRA NOT LIKE '%GENERATED%'",
            ['pelanggan']
        );

        foreach ($kolom as $k) {
            $nama = $k->nama;

            if (in_array($nama, self::DIISI_SERVICE, true)) {
                continue;
            }

            // Nama kolom masuk ke SQL mentah, jadi dibatasi huruf, angka, dan garis bawah
            if (!preg_match('/^[A-Za-z0-9_]+$/', $nama)) {
                continue;
            }

            DB::statement("ALTER TABLE `pelanggan` MODIFY COLUMN `{$nama}` {$k->tipe} NULL");
        }
    }

    public function down(): void
    {
        // Sengaja kosong: mengembalikan kolom menjadi NOT NULL akan gagal bila
        // sudah ada pelanggan dengan data kosong.
    }
};