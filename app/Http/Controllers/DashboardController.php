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
            // KPI UTAMA
            // =====================================================

            $jumlahPelanggan = Pelanggan::count();
            $jumlahPegawai = Pegawai::count();
            $jumlahPerawatan = Perawatan::count();
            $jumlahReservasi = Reservasi::count();


            // =====================================================
            // DATA RESERVASI
            // =====================================================

            // Reservasi hari ini
            $reservasiHariIni = Reservasi::whereDate(
                'tanggal',
                Carbon::today()
            )->count();


            // Reservasi mendatang
            $reservasiMendatang = Reservasi::whereDate(
                'tanggal',
                '>=',
                Carbon::today()
            )->count();


            // Reservasi menunggu
            $reservasiMenunggu = Reservasi::where(
                'status',
                'Menunggu'
            )->count();


            // Reservasi dikonfirmasi
            $reservasiDikonfirmasi = Reservasi::where(
                'status',
                'Dikonfirmasi'
            )->count();


            // Reservasi selesai
            $reservasiSelesai = Reservasi::where(
                'status',
                'Selesai'
            )->count();


            // Reservasi dibatalkan
            $reservasiDibatalkan = Reservasi::where(
                'status',
                'Dibatalkan'
            )->count();


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
            // CHART RESERVASI 7 HARI TERAKHIR
            // =====================================================

            $chartLabels = [];
            $chartValues = [];

            for ($i = 6; $i >= 0; $i--) {

                $tanggal = Carbon::today()->subDays($i);

                $jumlah = Reservasi::whereDate(
                    'tanggal',
                    $tanggal
                )->count();

                $chartLabels[] =
                    $tanggal->translatedFormat('d M');

                $chartValues[] =
                    $jumlah;
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
    // LIVE DATA DASHBOARD
    // =========================================================

    public function liveData()
    {
        try {

            return response()->json([

                'success' => true,

                'jumlahPelanggan' =>
                    Pelanggan::count(),

                'jumlahPegawai' =>
                    Pegawai::count(),

                'jumlahPerawatan' =>
                    Perawatan::count(),

                'jumlahReservasi' =>
                    Reservasi::count(),


                // =================================================
                // RESERVASI HARI INI
                // =================================================

                'reservasiHariIni' =>
                    Reservasi::whereDate(
                        'tanggal',
                        Carbon::today()
                    )->count(),


                // =================================================
                // RESERVASI MENDATANG
                // =================================================

                'reservasiMendatang' =>
                    Reservasi::whereDate(
                        'tanggal',
                        '>=',
                        Carbon::today()
                    )->count(),


                // =================================================
                // MENUNGGU
                // =================================================

                'reservasiMenunggu' =>
                    Reservasi::where(
                        'status',
                        'Menunggu'
                    )->count(),


                // =================================================
                // DIKONFIRMASI
                // =================================================

                'reservasiDikonfirmasi' =>
                    Reservasi::where(
                        'status',
                        'Dikonfirmasi'
                    )->count(),


                // =================================================
                // SELESAI
                // =================================================

                'reservasiSelesai' =>
                    Reservasi::where(
                        'status',
                        'Selesai'
                    )->count(),


                // =================================================
                // DIBATALKAN
                // =================================================

                'reservasiDibatalkan' =>
                    Reservasi::where(
                        'status',
                        'Dibatalkan'
                    )->count(),

            ]);


        } catch (\Exception $e) {

            Log::error('Dashboard Live Error', [

                'message' => $e->getMessage(),

            ]);


            return response()->json([

                'success' => false,

                'message' =>
                    'Gagal memuat data dashboard'

            ], 500);
        }
    }
}