<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RiwayatReservasiController extends Controller
{
    /**
     * Menampilkan seluruh riwayat reservasi
     */
    public function index(Request $request)
    {
        try {

            $query = Reservasi::with([
                'pelanggan',
                'perawatan',
                'pegawai'
            ]);

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if ($request->filled('keyword')) {

                $keyword = trim($request->keyword);

                $query->where(function ($q) use ($keyword) {

                    $q->whereHas('pelanggan', function ($q) use ($keyword) {

                        $q->where(
                            'nama',
                            'like',
                            '%' . $keyword . '%'
                        );

                    })

                    ->orWhereHas('perawatan', function ($q) use ($keyword) {

                        $q->where(
                            'nama',
                            'like',
                            '%' . $keyword . '%'
                        );

                    })

                    ->orWhereHas('pegawai', function ($q) use ($keyword) {

                        $q->where(
                            'nama',
                            'like',
                            '%' . $keyword . '%'
                        );

                    })

                    ->orWhere(
                        'status',
                        'like',
                        '%' . $keyword . '%'
                    );

                });
            }


            /*
            |--------------------------------------------------------------------------
            | FILTER STATUS
            |--------------------------------------------------------------------------
            */

            if ($request->filled('status')) {

                $query->where(
                    'status',
                    $request->status
                );

            }


            /*
            |--------------------------------------------------------------------------
            | FILTER TANGGAL
            |--------------------------------------------------------------------------
            */

            if ($request->filled('tanggal')) {

                $query->whereDate(
                    'tanggal',
                    $request->tanggal
                );

            }


            /*
            |--------------------------------------------------------------------------
            | DATA
            |--------------------------------------------------------------------------
            */

            $riwayats = $query
                ->orderByDesc('tanggal')
                ->orderByDesc('jam_mulai')
                ->paginate(5)
                ->withQueryString();


            /*
            |--------------------------------------------------------------------------
            | SUMMARY
            |--------------------------------------------------------------------------
            */

            $totalRiwayat = Reservasi::count();

            $totalMenunggu = Reservasi::where(
                'status',
                'Menunggu'
            )->count();

            $totalDikonfirmasi = Reservasi::where(
                'status',
                'Dikonfirmasi'
            )->count();

            $totalSelesai = Reservasi::where(
                'status',
                'Selesai'
            )->count();

            $totalDibatalkan = Reservasi::where(
                'status',
                'Dibatalkan'
            )->count();


            /*
            |--------------------------------------------------------------------------
            | VIEW
            |--------------------------------------------------------------------------
            */

            return view(
                'pages.riwayat-reservasi.index',
                compact(
                    'riwayats',
                    'totalRiwayat',
                    'totalMenunggu',
                    'totalDikonfirmasi',
                    'totalSelesai',
                    'totalDibatalkan'
                )
            );


        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan riwayat reservasi: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengambil data riwayat reservasi: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * Menampilkan detail riwayat reservasi
     */
    public function show(string $id)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | CARI DATA RESERVASI
            |--------------------------------------------------------------------------
            */

            $riwayat = Reservasi::with([
                'pelanggan',
                'perawatan',
                'pegawai'
            ])->find($id);


            /*
            |--------------------------------------------------------------------------
            | CEK DATA
            |--------------------------------------------------------------------------
            */

            if (!$riwayat) {

                return redirect()
                    ->route('riwayat-reservasi.index')
                    ->with(
                        'error',
                        'Data reservasi dengan ID #' .
                        $id .
                        ' tidak ditemukan.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN DETAIL.BLADE.PHP
            |--------------------------------------------------------------------------
            */

            return view(
                'pages.riwayat-reservasi.detail',
                compact('riwayat')
            );


        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan detail riwayat reservasi ID #' .
                $id .
                ': ' .
                $e->getMessage()
            );

            return redirect()
                ->route('riwayat-reservasi.index')
                ->with(
                    'error',
                    'Terjadi kesalahan: ' .
                    $e->getMessage()
                );
        }
    }
}