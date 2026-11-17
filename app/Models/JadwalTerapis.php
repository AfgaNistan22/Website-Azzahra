<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTerapis extends Model
{
    protected $table = 'jadwal_terapis';

    protected $fillable = [
        'nama_terapis',
        'spesialisasi',
        'foto',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];
}