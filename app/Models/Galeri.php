<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';

    protected $fillable = ['judul', 'kategori', 'foto', 'urutan', 'status'];

    /** Hanya foto yang ditampilkan ke pengunjung */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
}
