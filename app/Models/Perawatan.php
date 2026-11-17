<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatan';

    protected $fillable = [
        'nama',
        'kategori_id',
        'deskripsi',
        'durasi',
        'harga',
        'foto',
    ];

    protected $casts = [
        'kategori_id' => 'integer',
        'durasi' => 'integer',
        'harga' => 'integer',
    ];

    /**
     * Relasi ke kategori perawatan.
     */
    public function kategori()
    {
        return $this->belongsTo(
            KategoriPerawatan::class,
            'kategori_id'
        );
    }

    /**
     * Relasi ke reservasi.
     */
    public function reservasi()
    {
        return $this->belongsToMany(
            Reservasi::class,
            'reservasi_perawatan',
            'perawatan_id',
            'reservasi_id'
        )->withPivot([
            'jumlah',
            'harga',
            'durasi',
        ])->withTimestamps();
    }
}