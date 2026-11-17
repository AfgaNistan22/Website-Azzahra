<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo';

    protected $fillable = [
        'nama', 'perawatan_id', 'khusus_member', 'deskripsi', 'jenis',
        'nilai', 'tanggal_mulai', 'tanggal_selesai', 'status', 'foto',
    ];

    protected $casts = [
        'nilai'           => 'integer',
        'khusus_member'   => 'boolean',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi promo dengan perawatan
     * (perawatan_id NULL = promo berlaku untuk semua perawatan)
     */
    public function perawatan()
    {
        return $this->belongsTo(
            Perawatan::class,
            'perawatan_id'
        );
    }

    /**
     * Relasi promo dengan pembayaran
     */
    public function pembayarans()
    {
        return $this->hasMany(
            Pembayaran::class,
            'promo_id'
        );
    }

    /* =========================================================
       ACCESSOR
    ========================================================= */

    /** Promo berlaku untuk semua perawatan? */
    public function getBerlakuSemuaAttribute(): bool
    {
        return is_null($this->perawatan_id);
    }

    /** Nama perawatan untuk tampilan (tabel, detail, PDF) */
    public function getNamaPerawatanAttribute(): string
    {
        return $this->perawatan->nama ?? 'Semua Perawatan';
    }

    /** Sudah lewat tanggal selesai? */
    public function getSudahKedaluwarsaAttribute(): bool
    {
        return $this->tanggal_selesai
            && $this->tanggal_selesai->lt(today());
    }

    /** Status efektif: otomatis "Tidak Aktif" jika sudah lewat tanggal selesai */
    public function getStatusEfektifAttribute(): string
    {
        return $this->sudah_kedaluwarsa ? 'Tidak Aktif' : $this->status;
    }

    /* =========================================================
       SCOPE
    ========================================================= */

    /** Promo yang bisa dipakai pada tanggal tertentu (default: hari ini) */
    public function scopeBerlaku(Builder $query, $tanggal = null): Builder
    {
        $tanggal = $tanggal
            ? Carbon::parse($tanggal)->toDateString()
            : today()->toDateString();

        return $query->where('status', 'Aktif')
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal);
    }

    /** Promo semua perawatan (NULL) atau salah satu id perawatan yang diberikan */
    public function scopeUntukPerawatan(Builder $query, $perawatanIds): Builder
    {
        $ids = (array) $perawatanIds;

        return $query->where(function ($q) use ($ids) {
            $q->whereNull('perawatan_id')
              ->orWhereIn('perawatan_id', $ids);
        });
    }

    /** Promo yang sudah lewat tanggal selesai tetapi statusnya masih Aktif */
    public function scopeKedaluwarsa(Builder $query): Builder
    {
        return $query->where('status', 'Aktif')
            ->whereDate('tanggal_selesai', '<', today()->toDateString());
    }

    /**
     * Promo yang boleh dipakai pelanggan tertentu.
     * Bukan member (atau tamu tanpa data pelanggan) tidak melihat promo khusus member.
     */
    public function scopeUntukPelanggan(Builder $query, ?Pelanggan $pelanggan): Builder
    {
        $member = $pelanggan && $pelanggan->member === 'Ya';

        return $query->when(! $member, fn ($q) => $q->where('khusus_member', false));
    }
}