<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gaji;
use App\Models\Pajak;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nama',
        'no_telepon',
        'email',
        'alamat',
        'foto',
        'status',
    ];

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function gaji()
    {
        return $this->hasMany(
            Gaji::class,
            'pegawai_id'
        );
    }

    public function pajak()
    {
        return $this->hasMany(
            Pajak::class,
            'pegawai_id'
        );
    }
}