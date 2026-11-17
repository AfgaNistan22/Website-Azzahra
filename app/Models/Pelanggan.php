<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'jk',
        'tgl_lahir',
        'no_telepon',
        'email',
        'alamat',
        'foto',
        'member',
        'jumlah_kedatangan',
        'terakhir_datang',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'terakhir_datang' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
}