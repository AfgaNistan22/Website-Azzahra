<?php

namespace App\Services;

use App\Models\Perawatan;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Aturan penjadwalan reservasi yang dipakai bersama oleh pengecekan jadwal
 * (pratinjau di form) dan penyimpanan reservasi.
 *
 * Mengapa dipisah:
 * - Pratinjau dan penyimpanan memakai SATU aturan, jadi hasil "tersedia" di
 *   form tidak lagi bisa berbeda dengan keputusan saat reservasi disimpan.
 * - Semua perhitungan waktu memakai menit sejak tengah malam (integer).
 *   Cara lama memakai Carbon::createFromFormat('H:i', ...) tanpa tanda "!",
 *   yang mengisi detik dengan detik saat ini. Akibatnya overlap bisa meleset
 *   sekitar satu menit di batas toleransi 15 menit.
 */
class PenjadwalanReservasi
{
    /** Overlap sampai sejumlah menit ini masih diterima (pelanggan menunggu). */
    public const TOLERANSI_BENTROK = 15;

    public const JAM_BUKA  = '09:00';
    public const JAM_TUTUP = '17:00';

    /** Jarak antar kandidat slot alternatif. */
    public const LANGKAH_SLOT = 15;

    /** Jumlah maksimal satu jenis perawatan dalam satu reservasi. */
    public const MAKS_JUMLAH = 10;

    /** Zona waktu salon. */
    public const ZONA_WAKTU = 'Asia/Jakarta';


    /* =====================================================
     * WAKTU
     * ===================================================== */

    public function sekarang(): Carbon
    {
        return Carbon::now(self::ZONA_WAKTU);
    }

    /** "09:30" atau "09:30:00" menjadi 570. */
    public function keMenit(string $jam): int
    {
        $bagian = explode(':', substr($jam, 0, 5));

        return ((int) $bagian[0]) * 60 + (int) ($bagian[1] ?? 0);
    }

    /** 570 menjadi "09:30". */
    public function keJam(int $menit): string
    {
        return sprintf('%02d:%02d', intdiv($menit, 60), $menit % 60);
    }


    /* =====================================================
     * PERAWATAN
     * ===================================================== */

    /**
     * Mengubah input perawatan_ids[ID] = jumlah menjadi ringkasan
     * (ID, data pivot, total durasi, total harga).
     *
     * Mengembalikan null bila kosong, ada ID yang tidak ditemukan,
     * atau total durasinya tidak valid.
     */
    public function ringkasPerawatan(array $input): ?array
    {
        $jumlahPerId = [];

        foreach ($input as $id => $nilai) {
            $id = (int) $id;

            if ($id <= 0) {
                continue;
            }

            $jumlahPerId[$id] = max(1, min((int) $nilai, self::MAKS_JUMLAH));
        }

        if (empty($jumlahPerId)) {
            return null;
        }

        $perawatan = Perawatan::whereIn('id', array_keys($jumlahPerId))
            ->get()
            ->keyBy('id');

        if ($perawatan->count() !== count($jumlahPerId)) {
            return null;
        }

        $pivot  = [];
        $durasi = 0;
        $harga  = 0;

        foreach ($jumlahPerId as $id => $jumlah) {
            $item = $perawatan->get($id);

            $durasiItem = (int) $item->durasi;
            $hargaItem  = (int) $item->harga;

            $durasi += $durasiItem * $jumlah;
            $harga  += $hargaItem * $jumlah;

            // Snapshot harga dan durasi saat reservasi dibuat
            $pivot[$id] = [
                'jumlah' => $jumlah,
                'harga'  => $hargaItem,
                'durasi' => $durasiItem,
            ];
        }

        if ($durasi <= 0) {
            return null;
        }

        return [
            'ids'    => array_keys($pivot),
            'pivot'  => $pivot,
            'durasi' => $durasi,
            'harga'  => $harga,
        ];
    }


    /* =====================================================
     * VALIDASI WAKTU
     * ===================================================== */

    /**
     * Memeriksa tanggal dan jam terhadap jam operasional dan waktu sekarang.
     * Mengembalikan pesan galat, atau null bila semuanya wajar.
     */
    public function validasiWaktu(string $tanggal, int $mulai, int $selesai): ?string
    {
        $sekarang = $this->sekarang();
        $hariIni  = $sekarang->toDateString();

        if ($tanggal < $hariIni) {
            return 'Tanggal reservasi tidak boleh sebelum hari ini.';
        }

        if ($selesai <= $mulai) {
            return 'Jam selesai harus lebih besar dari jam mulai.';
        }

        if ($mulai < $this->keMenit(self::JAM_BUKA)) {
            return 'Jam mulai paling awal ' . self::JAM_BUKA . ' WIB.';
        }

        if ($selesai > $this->keMenit(self::JAM_TUTUP)) {
            return 'Perawatan selesai pukul ' . $this->keJam($selesai)
                . ', melewati jam operasional (' . self::JAM_BUKA
                . ' sampai ' . self::JAM_TUTUP . ' WIB).';
        }

        if ($tanggal === $hariIni && $mulai <= ($sekarang->hour * 60 + $sekarang->minute)) {
            return 'Jam mulai sudah lewat. Pilih jam yang lebih akhir.';
        }

        return null;
    }


    /* =====================================================
     * KETERSEDIAAN
     * ===================================================== */

    /** Reservasi aktif (bukan Dibatalkan) milik seorang terapis pada satu tanggal. */
    public function reservasiTerapis(string $tanggal, int $pegawaiId, ?int $excludeId = null): Collection
    {
        return Reservasi::whereDate('tanggal', $tanggal)
            ->where('pegawai_id', $pegawaiId)
            ->where('status', '!=', 'Dibatalkan')
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('jam_mulai')
            ->get(['id', 'jam_mulai', 'jam_selesai', 'status']);
    }

    /**
     * Aturan tunggal untuk pratinjau dan penyimpanan:
     *
     * - tersedia : tidak ada overlap sama sekali
     * - tunggu   : overlap terbesar masih <= TOLERANSI_BENTROK
     * - bentrok  : ada overlap > TOLERANSI_BENTROK
     *
     * Overlap dihitung per reservasi lain (bukan dijumlahkan), sama seperti
     * yang selalu dipakai saat penyimpanan.
     */
    public function cekKetersediaan(
        string $tanggal,
        int $pegawaiId,
        int $mulai,
        int $selesai,
        ?int $excludeId = null
    ): array {
        $terparah = null;
        $maks     = 0;

        foreach ($this->reservasiTerapis($tanggal, $pegawaiId, $excludeId) as $item) {
            $awal  = $this->keMenit($item->jam_mulai);
            $akhir = $this->keMenit($item->jam_selesai);

            $overlap = max(0, min($selesai, $akhir) - max($mulai, $awal));

            if ($overlap > $maks) {
                $maks = $overlap;

                $terparah = [
                    'id'          => $item->id,
                    'jam_mulai'   => $this->keJam($awal),
                    'jam_selesai' => $this->keJam($akhir),
                    'status'      => $item->status,
                    'overlap'     => $overlap,
                    // "sebelum": reservasi lain mulai lebih dulu, pelanggan menunggu.
                    // "sesudah": reservasi lain mulai belakangan, perawatan ini mepet.
                    'arah'        => $awal <= $mulai ? 'sebelum' : 'sesudah',
                ];
            }
        }

        if ($maks === 0) {
            $status = 'tersedia';
        } elseif ($maks <= self::TOLERANSI_BENTROK) {
            $status = 'tunggu';
        } else {
            $status = 'bentrok';
        }

        return [
            'status'                => $status,
            'wait_minutes'          => $status === 'tunggu' ? $maks : 0,
            'overlap_maks'          => $maks,
            'reservasi_berbenturan' => $terparah,
        ];
    }


    /* =====================================================
     * REKOMENDASI SLOT
     * ===================================================== */

    /**
     * Slot yang benar-benar kosong (tanpa overlap) pada hari yang sama,
     * diurutkan dari yang paling dekat dengan jam yang diminta.
     * Slot yang sudah lewat hari ini tidak ikut disarankan.
     */
    public function cariSlot(
        string $tanggal,
        int $pegawaiId,
        int $durasi,
        int $targetMulai,
        ?int $excludeId = null,
        int $batas = 3
    ): array {
        $sekarang = $this->sekarang();
        $hariIni  = $sekarang->toDateString();

        if ($tanggal < $hariIni || $durasi <= 0) {
            return [];
        }

        $buka  = $this->keMenit(self::JAM_BUKA);
        $tutup = $this->keMenit(self::JAM_TUTUP);

        $terpakai = $this->reservasiTerapis($tanggal, $pegawaiId, $excludeId)
            ->map(fn ($r) => [$this->keMenit($r->jam_mulai), $this->keMenit($r->jam_selesai)])
            ->all();

        $paling_awal = $buka;
        if ($tanggal === $hariIni) {
            $paling_awal = max($buka, $sekarang->hour * 60 + $sekarang->minute + 1);
        }

        $kandidat = [];

        for ($m = $buka; $m + $durasi <= $tutup; $m += self::LANGKAH_SLOT) {
            if ($m < $paling_awal) {
                continue;
            }

            $selesai = $m + $durasi;
            $bentrok = false;

            foreach ($terpakai as [$awal, $akhir]) {
                if ($m < $akhir && $selesai > $awal) {
                    $bentrok = true;
                    break;
                }
            }

            if (!$bentrok) {
                $kandidat[] = ['mulai' => $m, 'jarak' => abs($m - $targetMulai)];
            }
        }

        usort($kandidat, fn ($a, $b) => [$a['jarak'], $a['mulai']] <=> [$b['jarak'], $b['mulai']]);

        return array_map(fn ($k) => [
            'tanggal'     => $tanggal,
            'jam_mulai'   => $this->keJam($k['mulai']),
            'jam_selesai' => $this->keJam($k['mulai'] + $durasi),
        ], array_slice($kandidat, 0, $batas));
    }
}