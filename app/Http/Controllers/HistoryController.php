<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        try {

            $keyword       = $request->keyword;
            $aktivitas     = $request->aktivitas;
            $tanggal_awal  = $request->tanggal_awal;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = History::query();

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {

                    $q->where('user_name', 'like', "%{$keyword}%")
                    ->orWhere('modul', 'like', "%{$keyword}%")
                    ->orWhere('data_id', 'like', "%{$keyword}%");

                });
            }

            if ($aktivitas) {
                $query->where('aktivitas', $aktivitas);
            }

            if ($tanggal_awal && $tanggal_akhir) {

                $query->whereBetween('waktu', [
                    $tanggal_awal . ' 00:00:00',
                    $tanggal_akhir . ' 23:59:59'
                ]);

            } elseif ($tanggal_awal) {

                $query->whereDate('waktu', $tanggal_awal);

            } elseif ($tanggal_akhir) {

                $query->whereDate('waktu', $tanggal_akhir);
            }

            $data = $query
                ->orderByDesc('waktu')
                ->paginate(15)
                ->withQueryString();

            $totalAktivitas = History::count();
            $totalLogin     = History::where('aktivitas', 'Login')->count();
            $totalLogout    = History::where('aktivitas', 'Logout')->count();
            $totalTambah    = History::where('aktivitas', 'Tambah Data')->count();
            $totalEdit      = History::where('aktivitas', 'Edit Data')->count();
            $totalHapus     = History::where('aktivitas', 'Hapus Data')->count();
            $todayCount     = History::whereDate('waktu', now())->count();

            return view(
                'pages.history.index',
                compact(
                    'data',
                    'totalAktivitas',
                    'totalLogin',
                    'totalLogout',
                    'totalTambah',
                    'totalEdit',
                    'totalHapus',
                    'todayCount'
                )
            );

        } catch (\Throwable $e) {

            \Log::error($e);

            return back()->with(
                'error',
                'Terjadi kesalahan saat memuat history.'
            );
        }
    }
}