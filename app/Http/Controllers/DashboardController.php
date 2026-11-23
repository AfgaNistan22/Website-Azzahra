<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pegawai;
use App\Models\Perawatan;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dash(Request $request)
    {
        try {

            // =====================================================
            // KPI UTAMA & DATA RESERVASI (OPTIMIZED AGGREGATION)
            // =====================================================

            $stats = $this->getReservasiStats();

            $jumlahPelanggan       = Pelanggan::count();
            $jumlahPegawai         = Pegawai::count();
            $jumlahPerawatan       = Perawatan::count();
            $jumlahReservasi       = (int) $stats->total;

            $reservasiHariIni      = (int) $stats->hari_ini;
            $reservasiMendatang    = (int) $stats->mendatang;
            $reservasiMenunggu     = (int) $stats->menunggu;
            $reservasiDikonfirmasi = (int) $stats->dikonfirmasi;
            $reservasiSelesai      = (int) $stats->selesai;
            $reservasiDibatalkan   = (int) $stats->dibatalkan;

            // =====================================================
            // RESERVASI TERBARU
            // =====================================================

            $reservasiTerbaru = Reservasi::with([
                'pelanggan',
                'pegawai',
                'perawatan'
            ])
                ->orderByDesc('tanggal')
                ->orderByDesc('jam_mulai')
                ->limit(10)
                ->get();

            // =====================================================
            // CHART RESERVASI 7 HARI TERAKHIR (SINGLE QUERY GROUP BY)
            // =====================================================

            $enamHariLalu = Carbon::today()->subDays(6)->toDateString();
            $hariIni      = Carbon::today()->toDateString();

            $dataPerHari = Reservasi::whereDate('tanggal', '>=', $enamHariLalu)
                ->whereDate('tanggal', '<=', $hariIni)
                ->selectRaw('DATE(tanggal) as tgl, COUNT(*) as jumlah')
                ->groupBy('tgl')
                ->pluck('jumlah', 'tgl')
                ->all();

            $chartLabels = [];
            $chartValues = [];

            for ($i = 6; $i >= 0; $i--) {
                $tglObj        = Carbon::today()->subDays($i);
                $tglStr        = $tglObj->toDateString();
                $chartLabels[] = $tglObj->translatedFormat('d M');
                $chartValues[] = (int) ($dataPerHari[$tglStr] ?? 0);
            }


            // =====================================================
            // KONFIGURASI CHART
            // =====================================================

            $chartConfig = [

                'type' => 'bar',

                'data' => [

                    'labels' => $chartLabels,

                    'datasets' => [

                        [
                            'label' => 'Jumlah Reservasi',

                            'data' => $chartValues,

                            'backgroundColor' => '#dc3545',

                            'borderRadius' => 6,
                        ]

                    ]

                ],

                'options' => [

                    'responsive' => true,

                    'maintainAspectRatio' => false,

                    'plugins' => [

                        'legend' => [
                            'display' => false
                        ]

                    ],

                    'scales' => [

                        'y' => [

                            'beginAtZero' => true,

                            'ticks' => [

                                'stepSize' => 1

                            ]

                        ]

                    ]

                ]

            ];


            // =====================================================
            // RETURN DASHBOARD
            // =====================================================

            return view(
                'pages.dashboard',
                compact(
                    'jumlahPelanggan',
                    'jumlahPegawai',
                    'jumlahPerawatan',
                    'jumlahReservasi',

                    'reservasiHariIni',
                    'reservasiMendatang',

                    'reservasiMenunggu',
                    'reservasiDikonfirmasi',
                    'reservasiSelesai',
                    'reservasiDibatalkan',

                    'reservasiTerbaru',

                    'chartConfig'
                )
            );


        } catch (\Exception $e) {

            Log::error('Dashboard Error', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);


            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memuat dashboard.'
                );
        }
    }


    // =========================================================
    // LIVE DATA DASHBOARD (OPTIMIZED AGGREGATION)
    // =========================================================

    public function liveData()
    {
        try {
            $stats = $this->getReservasiStats();

            return response()->json([
                'success'               => true,
                'jumlahPelanggan'       => Pelanggan::count(),
                'jumlahPegawai'         => Pegawai::count(),
                'jumlahPerawatan'       => Perawatan::count(),
                'jumlahReservasi'       => (int) $stats->total,
                'reservasiHariIni'      => (int) $stats->hari_ini,
                'reservasiMendatang'    => (int) $stats->mendatang,
                'reservasiMenunggu'     => (int) $stats->menunggu,
                'reservasiDikonfirmasi' => (int) $stats->dikonfirmasi,
                'reservasiSelesai'      => (int) $stats->selesai,
                'reservasiDibatalkan'   => (int) $stats->dibatalkan,
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard Live Error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data dashboard'
            ], 500);
        }
    }

    /**
     * Mengambil seluruh metrik statistik reservasi dalam satu query agregasi cepat.
     */
    private function getReservasiStats(): object
    {
        $today = Carbon::today()->toDateString();

        return Reservasi::selectRaw("
            COUNT(*) as total,
            COUNT(CASE WHEN DATE(tanggal) = ? THEN 1 END) as hari_ini,
            COUNT(CASE WHEN DATE(tanggal) >= ? THEN 1 END) as mendatang,
            COUNT(CASE WHEN status = 'Menunggu' THEN 1 END) as menunggu,
            COUNT(CASE WHEN status = 'Dikonfirmasi' THEN 1 END) as dikonfirmasi,
            COUNT(CASE WHEN status = 'Selesai' THEN 1 END) as selesai,
            COUNT(CASE WHEN status = 'Dibatalkan' THEN 1 END) as dibatalkan
        ", [$today, $today])->first() ?? (object) [
            'total'        => 0,
            'hari_ini'     => 0,
            'mendatang'    => 0,
            'menunggu'     => 0,
            'dikonfirmasi' => 0,
            'selesai'      => 0,
            'dibatalkan'   => 0,
        ];
    }
}