<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPerawatan extends Model
{
    use HasFactory;

    protected $table = 'kategori_perawatan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'status',
    ];

    public function perawatans()
    {
        return $this->hasMany(
            Perawatan::class,
            'kategori_id'
        );
    }
}