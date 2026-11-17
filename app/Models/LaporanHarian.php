<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    protected $table = 'laporan_harian';

    protected $fillable = [
        'kode_laporan',
        'tanggal',
        'total_pemasukan',
        'total_pengeluaran',
        'laba_bersih',
        'jumlah_pemasukan',
        'jumlah_pengeluaran',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal'            => 'date',
        'total_pemasukan'    => 'integer',
        'total_pengeluaran'  => 'integer',
        'laba_bersih'        => 'integer',
        'jumlah_pemasukan'   => 'integer',
        'jumlah_pengeluaran' => 'integer',
    ];

    /**
     * Kode berurutan per bulan, contoh: LH-202610-0001.
     * Kalau kamu sudah punya generateKode() sendiri, pakai punyamu saja.
     */
    public static function generateKode(): string
    {
        $prefix = 'LH-' . now()->format('Ym') . '-';

        $terakhir = static::where('kode_laporan', 'like', $prefix . '%')
            ->orderByDesc('kode_laporan')
            ->value('kode_laporan');

        $urut = $terakhir ? ((int) substr($terakhir, -4)) + 1 : 1;

        return $prefix . str_pad((string) $urut, 4, '0', STR_PAD_LEFT);
    }
}