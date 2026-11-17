<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';

    protected $fillable = [
        'kode_pemasukan',
        'pembayaran_id',
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
        'pembayaran_id' => 'integer',
        'tanggal' => 'date',
        'nominal' => 'integer',
    ];

    /**
     * Relasi ke pembayaran
     */
    public function pembayaran()
    {
        return $this->belongsTo(
            Pembayaran::class,
            'pembayaran_id'
        );
    }

    /**
     * Sinkronisasi pemasukan otomatis dari pembayaran.
     *
     * Jika pembayaran Lunas:
     * - buat pemasukan baru jika belum ada
     * - update pemasukan jika sudah ada
     *
     * Jika pembayaran bukan Lunas:
     * - pemasukan terkait dibatalkan
     */
    public static function syncFromPembayaran(Pembayaran $pembayaran): ?self
    {
        // Jika pembayaran belum Lunas,
        // batalkan pemasukan yang terkait.
        if ($pembayaran->status !== 'Lunas') {
            return self::cancelFromPembayaran($pembayaran);
        }

        // Pastikan relasi reservasi tersedia.
        $pembayaran->loadMissing('reservasi');

        $tanggal = $pembayaran->tanggal_bayar
            ? $pembayaran->tanggal_bayar->format('Y-m-d')
            : now()->format('Y-m-d');

        $kodeReservasi = optional($pembayaran->reservasi)->kode_reservasi
            ?: 'RES-' . $pembayaran->reservasi_id;

        // Cari pemasukan berdasarkan pembayaran.
        $pemasukan = self::where(
            'pembayaran_id',
            $pembayaran->id
        )->first();

        $payload = [
            'kode_pemasukan' => $pemasukan?->kode_pemasukan
                ?: self::generateKode(),

            'sumber_jenis' => 'Reservasi',

            'tanggal' => $tanggal,

            'kategori' => 'Pembayaran Reservasi',

            'sumber' => $kodeReservasi,

            'nominal' => (int) $pembayaran->jumlah,

            'metode_pembayaran' => $pembayaran->metode_pembayaran,

            'status' => 'Diterima',

            'keterangan' => $pembayaran->catatan
                ?: 'Pemasukan otomatis dari pembayaran reservasi.',
        ];

        // Jika sudah ada, update.
        if ($pemasukan) {
            $pemasukan->update($payload);

            return $pemasukan->refresh();
        }

        // Jika belum ada, buat baru.
        return self::create(
            array_merge(
                [
                    'pembayaran_id' => $pembayaran->id,
                ],
                $payload
            )
        );
    }

    /**
     * Membatalkan pemasukan otomatis
     * ketika status pembayaran bukan Lunas.
     */
    public static function cancelFromPembayaran(
        Pembayaran $pembayaran
    ): ?self {
        $pemasukan = self::where(
            'pembayaran_id',
            $pembayaran->id
        )->first();

        if (!$pemasukan) {
            return null;
        }

        $pemasukan->update([
            'status' => 'Dibatalkan',
            'keterangan' => 'Pemasukan dibatalkan karena status pembayaran bukan Lunas.',
        ]);

        return $pemasukan->refresh();
    }

    /**
     * Generate kode pemasukan otomatis.
     * Format:
     * PMK-YYYYMMDD-XXXX
     */
    public static function generateKode(): string
    {
        do {
            $kode = 'PMK-' .
                now()->format('Ymd') .
                '-' .
                str_pad(
                    (string) random_int(1, 9999),
                    4,
                    '0',
                    STR_PAD_LEFT
                );
        } while (
            self::where(
                'kode_pemasukan',
                $kode
            )->exists()
        );

        return $kode;
    }

    /**
     * Mengecek apakah pemasukan berasal
     * dari pembayaran otomatis.
     */
    public function isOtomatis(): bool
    {
        return $this->sumber_jenis === 'Reservasi'
            && !is_null($this->pembayaran_id);
    }
}