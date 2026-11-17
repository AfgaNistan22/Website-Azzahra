<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LaporanMaster extends Model
{
    protected $table = 'laporan_master';

    protected $fillable = [
        'kode_laporan', 'kategori', 'tanggal_mulai', 'tanggal_selesai',
        'status', 'catatan', 'jumlah_data', 'kolom_nominal', 'total_nominal',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    /** Contoh: LM-261010-A7K2 */
    public static function generateKode(): string
    {
        do {
            $kode = 'LM-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        } while (static::where('kode_laporan', $kode)->exists());

        return $kode;
    }
}