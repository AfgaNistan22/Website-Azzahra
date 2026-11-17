<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $table = 'gaji';

    protected $fillable = [
        'pegawai_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'total_gaji',
        'status',
        'tanggal_bayar',
        'catatan',
    ];

    protected $casts = [
        'pegawai_id' => 'integer',
        'bulan' => 'integer',
        'tahun' => 'integer',
        'gaji_pokok' => 'integer',
        'tunjangan' => 'integer',
        'potongan' => 'integer',
        'total_gaji' => 'integer',
        'tanggal_bayar' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pegawai_id'
        );
    }
}