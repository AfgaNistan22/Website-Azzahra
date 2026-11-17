<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Tambahkan kolom kode_reservasi jika belum ada
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('reservasi', 'kode_reservasi')) {
            Schema::table('reservasi', function (Blueprint $table) {
                $table->string('kode_reservasi')
                    ->nullable()
                    ->after('id');
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Isi kode reservasi untuk data lama
        |--------------------------------------------------------------------------
        */

        $reservasi = DB::table('reservasi')
            ->whereNull('kode_reservasi')
            ->orWhere('kode_reservasi', '')
            ->orderBy('id')
            ->get();

        foreach ($reservasi as $item) {

            $kode = 'RSV-'
                . Carbon::parse($item->tanggal)->format('Ymd')
                . '-'
                . str_pad(
                    (string) $item->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            DB::table('reservasi')
                ->where('id', $item->id)
                ->update([
                    'kode_reservasi' => $kode
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Tambahkan UNIQUE
        |--------------------------------------------------------------------------
        */

        $indexes = DB::select("
            SHOW INDEX
            FROM reservasi
            WHERE Key_name = 'reservasi_kode_reservasi_unique'
        ");

        if (empty($indexes)) {

            Schema::table('reservasi', function (Blueprint $table) {

                $table->unique(
                    'kode_reservasi',
                    'reservasi_kode_reservasi_unique'
                );

            });

        }
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Hapus unique index jika ada
        |--------------------------------------------------------------------------
        */

        $indexes = DB::select("
            SHOW INDEX
            FROM reservasi
            WHERE Key_name = 'reservasi_kode_reservasi_unique'
        ");

        if (!empty($indexes)) {

            Schema::table('reservasi', function (Blueprint $table) {

                $table->dropUnique(
                    'reservasi_kode_reservasi_unique'
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Hapus kolom jika ada
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('reservasi', 'kode_reservasi')) {

            Schema::table('reservasi', function (Blueprint $table) {

                $table->dropColumn('kode_reservasi');

            });

        }
    }
};