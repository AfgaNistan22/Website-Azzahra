<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'alamat_cabang',
        'no_telepon',
        'email',
    ];

    protected $casts = [
        'kode_cabang' => 'integer',
    ];

    /**
     * Relasi ke reservasi jika nantinya
     * reservasi menggunakan cabang.
     */
    // public function reservasi()
    // {
    //     return $this->hasMany(Reservasi::class, 'kode_cabang', 'kode_cabang');
    // }
}