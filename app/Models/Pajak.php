<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;

    protected $table = 'pajak';

    protected $fillable = [
        'pegawai_id',
        'bulan',
        'tahun',
        'penghasilan_bruto',
        'ptkp',
        'pkp',
        'tarif_pajak',
        'jumlah_pajak',
        'status',
        'tanggal_bayar',
        'catatan',
    ];

    protected $casts = [
        'pegawai_id' => 'integer',
        'bulan' => 'integer',
        'tahun' => 'integer',
        'penghasilan_bruto' => 'integer',
        'ptkp' => 'integer',
        'pkp' => 'integer',
        'tarif_pajak' => 'decimal:2',
        'jumlah_pajak' => 'integer',
        'tanggal_bayar' => 'date',
    ];

    /**
     * Relasi ke Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pegawai_id'
        );
    }
}