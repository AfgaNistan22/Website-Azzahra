<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'id_transaksi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_transaksi',
        'jenis_transaksi',
        'noktp',
        'nominal',
        'rekening_tujuan',
        'bank_tujuan',
        'keterangan'
    ];

    public function nasabah()
    {
        return $this->belongsTo(
            Nasabah::class,
            'noktp',
            'noktp'
        );
    }

    public function transaksi()
    {
        return $this->hasMany(
            Transaksi::class,
            'noktp',
            'noktp'
        );
    }
}