<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanBulananStoreRequest;
use App\Http\Requests\LaporanBulananUpdateRequest;
use App\Models\LaporanBulanan;
use App\Services\LaporanBulananService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class LaporanBulananController extends Controller
{
    public function __construct(private LaporanBulananService $service)
    {
    }

    /**
     * Daftar laporan bulanan + ringkasan (dari SELURUH hasil filter).
     * Bulan yang punya transaksi tapi belum ada laporannya dibuat otomatis (Draft).
     */
    public function index(Request $request)
    {
        try {
            try {
                $this->service->sinkronkan(null, now()->toDateString());
            } catch (Throwable $e) {
                Log::warning('Sinkron otomatis laporan bulanan gagal.', ['message' => $e->getMessage()]);
            }

            $perPage = (int) $request->input('per_page', 10);

            if (!in_array($perPage, [5, 10, 25, 50, 100], true)) {
                $perPage = 10;
            }

            $query = $this->filterLaporan($request);

            $semua = (clone $query)->get(['id', 'periode']);

            $keuangan = $this->service->keuanganPerPeriode(
                $semua->map(fn ($l) => $this->kunci($l))->all()
            );

            $data = $query
                ->orderByDesc('periode')
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();

            $data->getCollection()->each(fn ($l) => $this->terapkanKeuangan($l, $keuangan));

            $ringkasan = collect($keuangan);

            $totalPemasukan    = (int) $ringkasan->sum('total_pemasukan');
            $totalPengeluaran  = (int) $ringkasan->sum('total_pengeluaran');
            $labaBersih        = $totalPemasukan - $totalPengeluaran;
            $jumlahPemasukan   = (int) $ringkasan->sum('jumlah_pemasukan');
            $jumlahPengeluaran = (int) $ringkasan->sum('jumlah_pengeluaran');
            $jumlahLaporan     = $semua->count();

            return view('pages.laporan.bulanan.index', compact(
                'data',
                'totalPemasukan',
                'totalPengeluaran',
                'labaBersih',
                'jumlahPemasukan',
                'jumlahPengeluaran',
                'jumlahLaporan'
            ));
        } catch (Throwable $e) {
            Log::error('Gagal menampilkan laporan bulanan.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Laporan bulanan gagal ditampilkan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('pages.laporan.bulanan.create');
    }

    /** Preview angka keuangan per bulan (AJAX di create/edit). */
    public function preview(Request $request)
    {
        try {
            $request->validate(['periode' => ['required', 'date_format:Y-m']]);

            return response()->json(array_merge(
                ['success' => true, 'periode' => $request->periode],
                $this->service->hitungKeuangan($request->periode)
            ));
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Gagal preview laporan bulanan.', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keuangan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(LaporanBulananStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $this->service->buatAtauPerbarui(
                $validated['periode'],
                $validated['status'],
                $validated['catatan'] ?? null
            );

            return redirect()
                ->route('bulanan.index')
                ->with('success', 'Laporan bulanan berhasil disimpan.');
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan laporan bulanan.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Laporan bulanan gagal disimpan: ' . $e->getMessage());
        }
    }

    /** Detail: angka dihitung ulang agar selalu sesuai transaksi terbaru. */
    public function show(LaporanBulanan $laporan_bulanan)
    {
        try {
            $p = $this->kunci($laporan_bulanan);

            $this->terapkanKeuangan($laporan_bulanan, [
                $p => $this->service->hitungKeuangan($p),
            ]);

            return view('pages.laporan.bulanan.detail', [
                'data'    => $laporan_bulanan,
                'rincian' => $this->service->rincianPerHari($p),
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menampilkan detail laporan bulanan.', ['message' => $e->getMessage()]);

            return back()->with('error', 'Detail laporan bulanan gagal ditampilkan: ' . $e->getMessage());
        }
    }

    public function edit(LaporanBulanan $laporan_bulanan)
    {
        return view('pages.laporan.bulanan.edit', ['data' => $laporan_bulanan]);
    }

    public function update(LaporanBulananUpdateRequest $request, LaporanBulanan $laporan_bulanan)
    {
        try {
            $validated = $request->validated();

            $laporan_bulanan->update(array_merge(
                $this->service->hitungKeuangan($validated['periode']),
                [
                    'kode_laporan' => LaporanBulanan::kodeUntuk($validated['periode']),
                    'periode'      => $validated['periode'],
                    'status'       => $validated['status'],
                    'catatan'      => $validated['catatan'] ?? null,
                ]
            ));

            return redirect()
                ->route('bulanan.show', $laporan_bulanan->id)
                ->with('success', 'Laporan bulanan berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('Gagal mengupdate laporan bulanan.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
                'id'      => $laporan_bulanan->id ?? null,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Laporan bulanan gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(LaporanBulanan $laporan_bulanan)
    {
        try {
            $laporan_bulanan->delete();

            return redirect()
                ->route('bulanan.index')
                ->with('success', 'Laporan bulanan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus laporan bulanan.', [
                'message' => $e->getMessage(),
                'id'      => $laporan_bulanan->id ?? null,
            ]);

            return back()->with('error', 'Laporan bulanan gagal dihapus: ' . $e->getMessage());
        }
    }

    /** Export PDF: per bulan berisi saldo awal, total, saldo akhir, dan rincian harian. */
    public function laporanPdf(Request $request)
    {
        try {
            $daftar = $this->filterLaporan($request)
                ->orderBy('periode')
                ->get(['id', 'periode'])
                ->map(fn ($l) => $this->kunci($l))
                ->all();

            $bulan = $this->service->susunBukuBulanan($daftar);

            $pdf = Pdf::loadView('pages.laporan.bulanan.laporan_pdf', compact('bulan'));
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('laporan-bulanan-' . now()->format('YmdHis') . '.pdf');
        } catch (Throwable $e) {
            Log::error('Gagal membuat PDF laporan bulanan.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Laporan PDF gagal dibuat: ' . $e->getMessage());
        }
    }

    /* ======================== HELPER ======================== */

    private function filterLaporan(Request $request): Builder
    {
        $query = LaporanBulanan::query();

        if ($request->filled('katakunci')) {
            $kata = trim($request->katakunci);

            $query->where(function ($q) use ($kata) {
                $q->where('kode_laporan', 'like', "%{$kata}%")
                  ->orWhere('catatan', 'like', "%{$kata}%");
            });
        }

        if ($this->bulanValid($request->bulan_mulai)) {
            $query->whereDate('periode', '>=', $this->service->awalBulan($request->bulan_mulai));
        }

        if ($this->bulanValid($request->bulan_selesai)) {
            $query->whereDate('periode', '<=', $this->service->awalBulan($request->bulan_selesai));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    private function bulanValid($nilai): bool
    {
        return is_string($nilai) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $nilai) === 1;
    }

    private function terapkanKeuangan(LaporanBulanan $laporan, array $keuangan): void
    {
        $angka = $keuangan[$this->kunci($laporan)] ?? null;

        if (!$angka) {
            return;
        }

        foreach ($angka as $kolom => $nilai) {
            $laporan->setAttribute($kolom, $nilai);
        }
    }

    private function kunci(LaporanBulanan $laporan): string
    {
        return $laporan->periode->copy()->startOfMonth()->toDateString();
    }
}