<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'kode_pengeluaran',
        'gaji_id',
        'pajak_id',
        'sumber_jenis',
        'tanggal',
        'kategori',
        'sumber',
        'nominal',
        'metode_pembayaran',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'gaji_id' => 'integer',
        'pajak_id' => 'integer',
        'tanggal' => 'date',
        'nominal' => 'integer',
    ];

    public function gaji()
    {
        return $this->belongsTo(Gaji::class, 'gaji_id');
    }

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'pajak_id');
    }

    public static function generateKode(): string
    {
        do {
            $kode = 'PGK-' . now()->format('Ymd') . '-' .
                str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('kode_pengeluaran', $kode)->exists());

        return $kode;
    }

    public static function syncFromGaji(Gaji $gaji): ?self
    {
        if ($gaji->status !== 'Dibayar') {
            return self::cancelFromGaji($gaji);
        }

        $gaji->loadMissing('pegawai');

        $tanggal = $gaji->tanggal_bayar
            ? $gaji->tanggal_bayar->format('Y-m-d')
            : now()->format('Y-m-d');

        $namaPegawai = optional($gaji->pegawai)->nama
            ?: 'Pegawai #' . $gaji->pegawai_id;

        $periode = sprintf('%02d/%04d', $gaji->bulan, $gaji->tahun);

        $pengeluaran = self::where('gaji_id', $gaji->id)->first();

        $payload = [
            'kode_pengeluaran' => $pengeluaran?->kode_pengeluaran ?: self::generateKode(),
            'sumber_jenis' => 'Gaji',
            'tanggal' => $tanggal,
            'kategori' => 'Gaji Pegawai',
            'sumber' => $namaPegawai . ' - ' . $periode,
            'nominal' => (int) $gaji->total_gaji,
            'metode_pembayaran' => null,
            'status' => 'Diterima',
            'keterangan' => $gaji->catatan ?: 'Pengeluaran otomatis dari pembayaran gaji.',
        ];

        if ($pengeluaran) {
            $pengeluaran->update($payload);
            return $pengeluaran->refresh();
        }

        return self::create(array_merge(
            ['gaji_id' => $gaji->id, 'pajak_id' => null],
            $payload
        ));
    }

    public static function cancelFromGaji(Gaji $gaji): ?self
    {
        $pengeluaran = self::where('gaji_id', $gaji->id)->first();

        if (!$pengeluaran) {
            return null;
        }

        $pengeluaran->update([
            'status' => 'Dibatalkan',
            'keterangan' => 'Pengeluaran gaji dibatalkan karena status gaji bukan Dibayar.',
        ]);

        return $pengeluaran->refresh();
    }

    public static function syncFromPajak(Pajak $pajak): ?self
    {
        if ($pajak->status !== 'Dibayar') {
            return self::cancelFromPajak($pajak);
        }

        $pajak->loadMissing('pegawai');

        $tanggal = $pajak->tanggal_bayar
            ? $pajak->tanggal_bayar->format('Y-m-d')
            : now()->format('Y-m-d');

        $namaPegawai = optional($pajak->pegawai)->nama
            ?: 'Pegawai #' . $pajak->pegawai_id;

        $periode = sprintf('%02d/%04d', $pajak->bulan, $pajak->tahun);

        $pengeluaran = self::where('pajak_id', $pajak->id)->first();

        $payload = [
            'kode_pengeluaran' => $pengeluaran?->kode_pengeluaran ?: self::generateKode(),
            'sumber_jenis' => 'Pajak',
            'tanggal' => $tanggal,
            'kategori' => 'Pajak',
            'sumber' => $namaPegawai . ' - ' . $periode,
            'nominal' => (int) $pajak->jumlah_pajak,
            'metode_pembayaran' => null,
            'status' => 'Diterima',
            'keterangan' => $pajak->catatan ?: 'Pengeluaran otomatis dari pembayaran pajak.',
        ];

        if ($pengeluaran) {
            $pengeluaran->update($payload);
            return $pengeluaran->refresh();
        }

        return self::create(array_merge(
            ['pajak_id' => $pajak->id, 'gaji_id' => null],
            $payload
        ));
    }

    public static function cancelFromPajak(Pajak $pajak): ?self
    {
        $pengeluaran = self::where('pajak_id', $pajak->id)->first();

        if (!$pengeluaran) {
            return null;
        }

        $pengeluaran->update([
            'status' => 'Dibatalkan',
            'keterangan' => 'Pengeluaran pajak dibatalkan karena status pajak bukan Dibayar.',
        ]);

        return $pengeluaran->refresh();
    }

    public function isOtomatis(): bool
    {
        return in_array($this->sumber_jenis, ['Gaji', 'Pajak'], true)
            && ($this->gaji_id !== null || $this->pajak_id !== null);
    }
}
