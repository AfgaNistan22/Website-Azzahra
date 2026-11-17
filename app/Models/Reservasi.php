<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasi';


    protected $fillable = [
        'kode_reservasi',
        'pelanggan_id',
        'perawatan_id',
        'pegawai_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan',
    ];


    protected $casts = [
        'tanggal' => 'date',
    ];


    /**
     * Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(
            Pelanggan::class,
            'pelanggan_id'
        );
    }


    /**
     * Perawatan utama / kompatibilitas database lama
     */
    public function perawatan()
    {
        return $this->belongsTo(
            Perawatan::class,
            'perawatan_id'
        );
    }


    /**
     * Semua perawatan dalam reservasi.
     */
    public function perawatans()
    {
        return $this->belongsToMany(
            Perawatan::class,
            'reservasi_perawatan',
            'reservasi_id',
            'perawatan_id'
        )
        ->withPivot([
            'jumlah',
            'harga',
            'durasi'
        ])
        ->withTimestamps();
    }


    /**
     * Pegawai / Terapis
     */
    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pegawai_id'
        );
    }


    /**
     * Pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'reservasi_id'
        );
    }
}