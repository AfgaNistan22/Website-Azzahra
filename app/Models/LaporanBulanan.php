<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LaporanBulanan extends Model
{
    protected $table = 'laporan_bulanan';

    protected $fillable = [
        'kode_laporan', 'periode', 'status', 'catatan',
        'total_pemasukan', 'total_pengeluaran', 'laba_bersih',
        'jumlah_pemasukan', 'jumlah_pengeluaran',
    ];

    protected $casts = [
        'periode' => 'date',
    ];

    /** Kode mengikuti periode, contoh: LB-202610 (satu bulan = satu kode). */
    public static function kodeUntuk(string $periode): string
    {
        return 'LB-' . Carbon::parse($periode)->format('Ym');
    }
}