<?php

namespace App\Services;

use App\Models\LaporanBulanan;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanBulananService
{
    private const FORMAT_BULAN = "DATE_FORMAT(tanggal, '%Y-%m-01')";

    /** Terima 'Y-m' atau 'Y-m-d', kembalikan 'Y-m-01'. */
    public function awalBulan(string $periode): string
    {
        $tanggal = preg_match('/^\d{4}-\d{2}$/', $periode)
            ? Carbon::createFromFormat('!Y-m', $periode)
            : Carbon::parse($periode);

        return $tanggal->startOfMonth()->toDateString();
    }

    public function hitungKeuangan(string $periode): array
    {
        $p = $this->awalBulan($periode);

        return $this->keuanganPerPeriode([$p])[$p];
    }

    /**
     * Keuangan banyak bulan sekaligus (2 query).
     *
     * @param  string[]  $daftarPeriode
     * @return array<string, array>  dikunci 'Y-m-01'
     */
    public function keuanganPerPeriode(array $daftarPeriode): array
    {
        $daftarPeriode = array_values(array_unique(
            array_map(fn ($p) => $this->awalBulan($p), $daftarPeriode)
        ));

        if (empty($daftarPeriode)) {
            return [];
        }

        $ambil = fn (string $model) => $model::query()
            ->where('status', 'Diterima')
            ->whereIn(DB::raw(self::FORMAT_BULAN), $daftarPeriode)
            ->selectRaw(self::FORMAT_BULAN . ' as bln, COALESCE(SUM(nominal), 0) as total, COUNT(*) as jumlah')
            ->groupBy('bln')
            ->get()
            ->keyBy('bln');

        $masuk  = $ambil(Pemasukan::class);
        $keluar = $ambil(Pengeluaran::class);

        $hasil = [];

        foreach ($daftarPeriode as $p) {
            $totalMasuk  = (int) ($masuk->get($p)?->total ?? 0);
            $totalKeluar = (int) ($keluar->get($p)?->total ?? 0);

            $hasil[$p] = [
                'total_pemasukan'    => $totalMasuk,
                'total_pengeluaran'  => $totalKeluar,
                'laba_bersih'        => $totalMasuk - $totalKeluar,
                'jumlah_pemasukan'   => (int) ($masuk->get($p)?->jumlah ?? 0),
                'jumlah_pengeluaran' => (int) ($keluar->get($p)?->jumlah ?? 0),
            ];
        }

        return $hasil;
    }

    /**
     * Rincian per hari dalam satu bulan (hanya hari yang ada transaksi).
     */
    public function rincianPerHari(string $periode): array
    {
        $awal  = $this->awalBulan($periode);
        $akhir = Carbon::parse($awal)->endOfMonth()->toDateString();

        $ambil = fn (string $model) => $model::query()
            ->where('status', 'Diterima')
            ->whereDate('tanggal', '>=', $awal)
            ->whereDate('tanggal', '<=', $akhir)
            ->selectRaw('DATE(tanggal) as tgl, COALESCE(SUM(nominal), 0) as total, COUNT(*) as jumlah')
            ->groupBy('tgl')
            ->get()
            ->keyBy('tgl');

        $masuk  = $ambil(Pemasukan::class);
        $keluar = $ambil(Pengeluaran::class);

        $hasil = [];

        foreach ($masuk->keys()->merge($keluar->keys())->unique()->sort()->values() as $tgl) {
            $totalMasuk  = (int) ($masuk->get($tgl)?->total ?? 0);
            $totalKeluar = (int) ($keluar->get($tgl)?->total ?? 0);

            $hasil[] = [
                'tanggal'            => Carbon::parse($tgl),
                'total_pemasukan'    => $totalMasuk,
                'total_pengeluaran'  => $totalKeluar,
                'laba_bersih'        => $totalMasuk - $totalKeluar,
                'jumlah_pemasukan'   => (int) ($masuk->get($tgl)?->jumlah ?? 0),
                'jumlah_pengeluaran' => (int) ($keluar->get($tgl)?->jumlah ?? 0),
            ];
        }

        return $hasil;
    }

    /** Saldo (pemasukan - pengeluaran) seluruh transaksi Diterima sebelum tanggal tertentu. */
    public function saldoSebelum(string $tanggal): int
    {
        $jumlah = fn (string $model) => (int) $model::query()
            ->where('status', 'Diterima')
            ->whereDate('tanggal', '<', $tanggal)
            ->sum('nominal');

        return $jumlah(Pemasukan::class) - $jumlah(Pengeluaran::class);
    }

    /**
     * Data untuk PDF: per bulan berisi saldo awal, total, saldo akhir, dan rincian harian.
     *
     * @param  string[]  $daftarPeriode
     */
    public function susunBukuBulanan(array $daftarPeriode): array
    {
        $daftarPeriode = array_values(array_unique(
            array_map(fn ($p) => $this->awalBulan($p), $daftarPeriode)
        ));
        sort($daftarPeriode);

        $keuangan = $this->keuanganPerPeriode($daftarPeriode);

        $hasil = [];

        foreach ($daftarPeriode as $p) {
            $saldoAwal = $this->saldoSebelum($p);

            $hasil[] = array_merge($keuangan[$p], [
                'periode'     => Carbon::parse($p),
                'saldo_awal'  => $saldoAwal,
                'saldo_akhir' => $saldoAwal + $keuangan[$p]['laba_bersih'],
                'hari'        => $this->rincianPerHari($p),
            ]);
        }

        return $hasil;
    }

    /**
     * Buat laporan untuk satu bulan, atau perbarui angkanya kalau sudah ada.
     * Laporan berstatus Final tidak disentuh lagi.
     */
    public function buatAtauPerbarui(
        string $periode,
        string $status = 'Draft',
        ?string $catatan = null
    ): LaporanBulanan {
        $p = $this->awalBulan($periode);

        return DB::transaction(function () use ($p, $status, $catatan) {
            $laporan  = LaporanBulanan::whereDate('periode', $p)->lockForUpdate()->first();
            $keuangan = $this->hitungKeuangan($p);

            if (!$laporan) {
                return LaporanBulanan::create(array_merge($keuangan, [
                    'kode_laporan' => LaporanBulanan::kodeUntuk($p),
                    'periode'      => $p,
                    'status'       => $status,
                    'catatan'      => $catatan,
                ]));
            }

            if ($laporan->status === 'Final') {
                return $laporan;
            }

            $laporan->update(array_merge($keuangan, ['status' => $status]));

            return $laporan;
        });
    }

    /**
     * Buatkan laporan Draft untuk semua bulan yang punya transaksi
     * (status Diterima) tetapi belum punya laporan.
     *
     * @return int jumlah laporan yang dibuat
     */
    public function sinkronkan(?string $sejak = null, ?string $sampai = null): int
    {
        $sejak  = $sejak ? $this->awalBulan($sejak) : null;
        $sampai = $sampai ? Carbon::parse($this->awalBulan($sampai))->endOfMonth()->toDateString() : null;

        $ambilBulan = fn (string $model) => $model::query()
            ->where('status', 'Diterima')
            ->when($sejak, fn ($q) => $q->whereDate('tanggal', '>=', $sejak))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal', '<=', $sampai))
            ->selectRaw('DISTINCT ' . self::FORMAT_BULAN . ' as bln')
            ->pluck('bln');

        $semua = $ambilBulan(Pemasukan::class)
            ->merge($ambilBulan(Pengeluaran::class))
            ->unique();

        $sudahAda = LaporanBulanan::query()
            ->get(['periode'])
            ->map(fn ($l) => $l->periode->toDateString());

        $kurang = $semua->diff($sudahAda)->sort()->values();

        foreach ($kurang as $p) {
            $this->buatAtauPerbarui($p, 'Draft');
        }

        return $kurang->count();
    }
}