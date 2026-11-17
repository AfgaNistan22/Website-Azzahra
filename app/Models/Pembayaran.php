<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'reservasi_id',
        'promo_id',
        'tanggal_bayar',
        'metode_pembayaran',
        'jumlah',
        'jumlah_sebelum_diskon',
        'jumlah_diskon',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah' => 'integer',
        'jumlah_sebelum_diskon' => 'integer',
        'jumlah_diskon' => 'integer',
    ];

    public function reservasi()
    {
        return $this->belongsTo(
            Reservasi::class,
            'reservasi_id'
        );
    }

    public function promo()
    {
        return $this->belongsTo(
            Promo::class,
            'promo_id'
        );
    }
}