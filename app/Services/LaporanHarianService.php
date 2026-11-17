<?php

namespace App\Services;

use App\Models\LaporanHarian;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanHarianService
{
    public function hitungKeuangan(string $tanggal): array
    {
        $tgl = Carbon::parse($tanggal)->toDateString();

        return $this->keuanganPerTanggal([$tgl])[$tgl];
    }

    public function keuanganPerTanggal(array $daftarTanggal): array
    {
        $daftarTanggal = array_values(array_unique($daftarTanggal));

        if (empty($daftarTanggal)) {
            return [];
        }

        $ambil = fn (string $model) => $model::query()
            ->where('status', 'Diterima')
            ->whereIn(DB::raw('DATE(tanggal)'), $daftarTanggal)
            ->selectRaw('DATE(tanggal) as tgl, COALESCE(SUM(nominal), 0) as total, COUNT(*) as jumlah')
            ->groupBy('tgl')
            ->get()
            ->keyBy('tgl');

        $masuk  = $ambil(Pemasukan::class);
        $keluar = $ambil(Pengeluaran::class);

        $hasil = [];

        foreach ($daftarTanggal as $tgl) {
            $totalMasuk  = (int) ($masuk->get($tgl)?->total ?? 0);
            $totalKeluar = (int) ($keluar->get($tgl)?->total ?? 0);

            $hasil[$tgl] = [
                'total_pemasukan'    => $totalMasuk,
                'total_pengeluaran'  => $totalKeluar,
                'laba_bersih'        => $totalMasuk - $totalKeluar,
                'jumlah_pemasukan'   => (int) ($masuk->get($tgl)?->jumlah ?? 0),
                'jumlah_pengeluaran' => (int) ($keluar->get($tgl)?->jumlah ?? 0),
            ];
        }

        return $hasil;
    }

    /**
     * Buat laporan untuk satu tanggal, atau perbarui angkanya kalau sudah ada.
     * Laporan berstatus Final tidak disentuh lagi (angkanya "dikunci").
     */
    public function buatAtauPerbarui(
        string $tanggal,
        string $status = 'Draft',
        ?string $catatan = null
    ): LaporanHarian {
        $tgl = Carbon::parse($tanggal)->toDateString();

        return DB::transaction(function () use ($tgl, $status, $catatan) {
            $laporan  = LaporanHarian::whereDate('tanggal', $tgl)->lockForUpdate()->first();
            $keuangan = $this->hitungKeuangan($tgl);

            if (!$laporan) {
                return LaporanHarian::create(array_merge($keuangan, [
                    'kode_laporan' => LaporanHarian::generateKode(),
                    'tanggal'      => $tgl,
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
     * Buatkan laporan Draft untuk semua tanggal yang punya transaksi
     * (status Diterima) tetapi belum punya laporan.
     *
     * @return int jumlah laporan yang dibuat
     */
    public function sinkronkan(?string $sejak = null, ?string $sampai = null): int
    {
        $ambilTanggal = fn (string $model) => $model::query()
            ->where('status', 'Diterima')
            ->when($sejak, fn ($q) => $q->whereDate('tanggal', '>=', $sejak))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal', '<=', $sampai))
            ->selectRaw('DATE(tanggal) as tgl')
            ->distinct()
            ->pluck('tgl');

        $semua = $ambilTanggal(Pemasukan::class)
            ->merge($ambilTanggal(Pengeluaran::class))
            ->map(fn ($t) => Carbon::parse($t)->toDateString())
            ->unique();

        $sudahAda = LaporanHarian::query()
            ->selectRaw('DATE(tanggal) as tgl')
            ->pluck('tgl')
            ->map(fn ($t) => Carbon::parse($t)->toDateString());

        $kurang = $semua->diff($sudahAda)->sort()->values();

        foreach ($kurang as $tgl) {
            $this->buatAtauPerbarui($tgl, 'Draft');
        }

        return $kurang->count();
    }
}