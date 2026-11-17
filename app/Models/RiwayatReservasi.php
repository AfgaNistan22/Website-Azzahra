<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatReservasi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_reservasi';

    protected $fillable = [
        'reservasi_id',
        'pelanggan_id',
        'perawatan_id',
        'pegawai_id',
        'nama_pelanggan',
        'nama_perawatan',
        'nama_pegawai',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan',
        'tanggal_riwayat',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_riwayat' => 'datetime',
    ];

    /**
     * Relasi ke reservasi asli
     */
    public function reservasi()
    {
        return $this->belongsTo(
            Reservasi::class,
            'reservasi_id'
        );
    }

    /**
     * Relasi ke pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(
            Pelanggan::class,
            'pelanggan_id'
        );
    }

    /**
     * Relasi ke perawatan
     */
    public function perawatan()
    {
        return $this->belongsTo(
            Perawatan::class,
            'perawatan_id'
        );
    }

    /**
     * Relasi ke pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pegawai_id'
        );
    }
}