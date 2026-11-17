<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayarans';

    protected $fillable = [
        'metode',   // nama bank / e-wallet, contoh: BCA, QRIS
        'norek',    // nomor rekening / nomor akun
        'an',       // atas nama
        'tipe',     // Transfer atau QRIS
        'logo',     // path di storage/app/public (opsional)
        'qris',     // path gambar QRIS di storage/app/public (khusus tipe QRIS)
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /** Hanya metode yang aktif */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}