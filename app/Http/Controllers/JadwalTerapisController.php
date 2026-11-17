<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JadwalTerapisController extends Controller
{
    public function index(Request $request)
    {
        // Bulan dan tahun yang dipilih
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // Validasi nilai bulan dan tahun
        if ($bulan < 1 || $bulan > 12) {
            $bulan = now()->month;
        }

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = now()->year;
        }

        $awalBulan = Carbon::createFromDate($tahun, $bulan, 1)
            ->startOfMonth();

        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $pencarian = trim((string) $request->input('cari', ''));

        $query = Reservasi::query()
            ->with([
                'pelanggan',
                'pegawai',
                'perawatan',
                'perawatans',
            ])
            ->whereBetween('tanggal', [
                $awalBulan->toDateString(),
                $akhirBulan->toDateString(),
            ])
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhereNotIn('status', [
                        'Dibatalkan',
                        'Dibatalkan Pelanggan',
                        'Dibatalkan Admin',
                    ]);
            });

        // Pencarian nama terapis, pelanggan, atau layanan
        if ($pencarian !== '') {
            $query->where(function ($q) use ($pencarian) {
                $q->whereHas('pegawai', function ($sub) use ($pencarian) {
                    $sub->where('nama', 'like', "%{$pencarian}%");
                })
                ->orWhereHas('pelanggan', function ($sub) use ($pencarian) {
                    $sub->where('nama', 'like', "%{$pencarian}%");
                })
                ->orWhereHas('perawatan', function ($sub) use ($pencarian) {
                    $sub->where('nama', 'like', "%{$pencarian}%");
                })
                ->orWhereHas('perawatans', function ($sub) use ($pencarian) {
                    $sub->where('nama', 'like', "%{$pencarian}%");
                });
            });
        }

        $reservasi = $query
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // Ringkasan jadwal bulan terpilih
        $totalJadwal = $reservasi->count();

        $totalDikonfirmasi = $reservasi->filter(
            fn ($item) => strtolower($item->status ?? '') === 'dikonfirmasi'
        )->count();

        $totalMenunggu = $reservasi->filter(
            fn ($item) => strtolower($item->status ?? '') === 'menunggu'
        )->count();

        // Data kalender
        $jumlahHari = $awalBulan->daysInMonth;
        $offsetHari = $awalBulan->dayOfWeekIso - 1;

        $kalender = [];

        for ($i = 0; $i < $offsetHari; $i++) {
            $kalender[] = null;
        }

        for ($hari = 1; $hari <= $jumlahHari; $hari++) {
            $kalender[] = $hari;
        }

        $kalender = array_chunk(
            array_pad(
                $kalender,
                (int) ceil(count($kalender) / 7) * 7,
                null
            ),
            7
        );

        $bulanSebelumnya = $awalBulan->copy()->subMonth();
        $bulanBerikutnya = $awalBulan->copy()->addMonth();

        return view('pages.jadwal-terapis.index', compact(
            'reservasi',
            'bulan',
            'tahun',
            'awalBulan',
            'akhirBulan',
            'pencarian',
            'totalJadwal',
            'totalDikonfirmasi',
            'totalMenunggu',
            'kalender',
            'bulanSebelumnya',
            'bulanBerikutnya'
        ));
    }
}