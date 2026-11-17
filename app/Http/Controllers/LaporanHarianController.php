<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanHarianStoreRequest;
use App\Http\Requests\LaporanHarianUpdateRequest;
use App\Models\LaporanHarian;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Services\LaporanHarianService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Throwable;

class LaporanHarianController extends Controller
{
    public function __construct(private LaporanHarianService $service)
    {
    }

    /**
     * Daftar laporan harian + ringkasan.
     * Laporan untuk tanggal yang punya transaksi tetapi belum ada laporannya
     * dibuat otomatis (Draft) sebelum daftar ditampilkan.
     * Ringkasan dihitung dari SELURUH hasil filter, bukan cuma halaman aktif.
     */
    public function index(Request $request)
    {
        try {
            // Sinkron otomatis: gagal sinkron tidak boleh membuat halaman error
            try {
                $this->service->sinkronkan(null, now()->toDateString());
            } catch (Throwable $e) {
                Log::warning('Sinkron otomatis laporan harian gagal.', [
                    'message' => $e->getMessage(),
                ]);
            }

            $perPage = (int) $request->input('per_page', 10);

            if (!in_array($perPage, [5, 10, 25, 50, 100], true)) {
                $perPage = 10;
            }

            $query = $this->filterLaporan($request);

            // Semua tanggal hasil filter (untuk ringkasan)
            $semua = (clone $query)->get(['id', 'tanggal']);

            $keuangan = $this->keuanganPerTanggal(
                $semua->map(fn ($l) => $this->kunciTanggal($l))->all()
            );

            // Halaman aktif
            $data = $query
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();

            $data->getCollection()->each(
                fn ($l) => $this->terapkanKeuangan($l, $keuangan)
            );

            $ringkasan = collect($keuangan);

            $totalPemasukan    = (int) $ringkasan->sum('total_pemasukan');
            $totalPengeluaran  = (int) $ringkasan->sum('total_pengeluaran');
            $labaBersih        = $totalPemasukan - $totalPengeluaran;
            $jumlahPemasukan   = (int) $ringkasan->sum('jumlah_pemasukan');
            $jumlahPengeluaran = (int) $ringkasan->sum('jumlah_pengeluaran');
            $jumlahLaporan     = $semua->count();

            return view('pages.laporan.harian.index', compact(
                'data',
                'totalPemasukan',
                'totalPengeluaran',
                'labaBersih',
                'jumlahPemasukan',
                'jumlahPengeluaran',
                'jumlahLaporan'
            ));
        } catch (Throwable $e) {
            Log::error('Gagal menampilkan laporan harian.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Laporan harian gagal ditampilkan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('pages.laporan.harian.create');
    }

    /**
     * Preview angka keuangan per tanggal (dipanggil AJAX di create/edit).
     */
    public function preview(Request $request)
    {
        try {
            $request->validate(['tanggal' => ['required', 'date']]);

            $keuangan = $this->hitungKeuangan($request->tanggal);

            return response()->json(array_merge(
                ['success' => true, 'tanggal' => $request->tanggal],
                $keuangan
            ));
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Gagal preview laporan harian.', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keuangan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simpan laporan baru (manual).
     * Satu tanggal = satu laporan. Duplikat sudah ditolak StoreRequest.
     */
    public function store(LaporanHarianStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $keuangan = $this->hitungKeuangan($validated['tanggal']);

            LaporanHarian::create(array_merge($keuangan, [
                'kode_laporan' => LaporanHarian::generateKode(),
                'tanggal'      => $validated['tanggal'],
                'status'       => $validated['status'],
                'catatan'      => $validated['catatan'] ?? null,
            ]));

            return redirect()
                ->route('harian.index')
                ->with('success', 'Laporan harian berhasil disimpan.');
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan laporan harian.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Laporan harian gagal disimpan: ' . $e->getMessage());
        }
    }

    /**
     * Detail laporan. Angka dihitung ulang agar selalu sesuai transaksi terbaru.
     */
    public function show(LaporanHarian $laporan_harian)
    {
        try {
            $tgl = $this->kunciTanggal($laporan_harian);

            $this->terapkanKeuangan($laporan_harian, [
                $tgl => $this->hitungKeuangan($tgl),
            ]);

            return view('pages.laporan.harian.detail', ['data' => $laporan_harian]);
        } catch (Throwable $e) {
            Log::error('Gagal menampilkan detail laporan harian.', [
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Detail laporan harian gagal ditampilkan: ' . $e->getMessage());
        }
    }

    public function edit(LaporanHarian $laporan_harian)
    {
        return view('pages.laporan.harian.edit', ['data' => $laporan_harian]);
    }

    public function update(LaporanHarianUpdateRequest $request, LaporanHarian $laporan_harian)
    {
        try {
            $validated = $request->validated();

            $keuangan = $this->hitungKeuangan($validated['tanggal']);

            $laporan_harian->update(array_merge($keuangan, [
                'tanggal' => $validated['tanggal'],
                'status'  => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]));

            return redirect()
                ->route('harian.show', $laporan_harian->id)
                ->with('success', 'Laporan harian berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('Gagal mengupdate laporan harian.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
                'id'      => $laporan_harian->id ?? null,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Laporan harian gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(LaporanHarian $laporan_harian)
    {
        try {
            $laporan_harian->delete();

            return redirect()
                ->route('harian.index')
                ->with('success', 'Laporan harian berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus laporan harian.', [
                'message' => $e->getMessage(),
                'id'      => $laporan_harian->id ?? null,
            ]);

            return back()->with('error', 'Laporan harian gagal dihapus: ' . $e->getMessage());
        }
    }

    /**
     * Export PDF buku harian: per tanggal berisi saldo awal,
     * pendapatan (debet), pengeluaran (kredit), dan saldo berjalan.
     */
    public function laporanPdf(Request $request)
    {
        try {
            $laporan = $this->filterLaporan($request)
                ->orderBy('tanggal')
                ->get(['id', 'tanggal']);

            $daftarTanggal = $laporan
                ->map(fn ($l) => $this->kunciTanggal($l))
                ->unique()
                ->values()
                ->all();

            $hari = $this->susunBukuHarian($daftarTanggal);

            $pdf = Pdf::loadView('pages.laporan.harian.laporan_pdf', compact('hari'));

            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('laporan-harian-' . now()->format('YmdHis') . '.pdf');
        } catch (Throwable $e) {
            Log::error('Gagal membuat PDF laporan harian.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Laporan PDF gagal dibuat: ' . $e->getMessage());
        }
    }

    /* ======================================================================
     | HELPER
     ====================================================================== */

    /**
     * Filter bersama untuk index dan PDF.
     */
    private function filterLaporan(Request $request): Builder
    {
        $query = LaporanHarian::query();

        if ($request->filled('katakunci')) {
            $kata = trim($request->katakunci);

            $query->where(function ($q) use ($kata) {
                $q->where('kode_laporan', 'like', "%{$kata}%")
                  ->orWhere('catatan', 'like', "%{$kata}%");
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    /**
     * Keuangan untuk SATU tanggal (didelegasikan ke service).
     */
    private function hitungKeuangan(string $tanggal): array
    {
        return $this->service->hitungKeuangan($tanggal);
    }

    /**
     * Keuangan untuk BANYAK tanggal sekaligus (didelegasikan ke service).
     *
     * @param  string[]  $daftarTanggal  format Y-m-d
     * @return array<string, array>      dikunci per tanggal
     */
    private function keuanganPerTanggal(array $daftarTanggal): array
    {
        return $this->service->keuanganPerTanggal($daftarTanggal);
    }

    /**
     * Tempel angka hasil hitung ke model (hanya tampilan, tidak disimpan).
     */
    private function terapkanKeuangan(LaporanHarian $laporan, array $keuangan): void
    {
        $angka = $keuangan[$this->kunciTanggal($laporan)] ?? null;

        if (!$angka) {
            return;
        }

        foreach ($angka as $kolom => $nilai) {
            $laporan->setAttribute($kolom, $nilai);
        }
    }

    private function kunciTanggal(LaporanHarian $laporan): string
    {
        return Carbon::parse($laporan->tanggal)->toDateString();
    }

    /**
     * Susun data buku harian.
     *
     * Saldo awal tiap tanggal = seluruh transaksi Diterima SEBELUM tanggal itu
     * (bukan cuma yang tampil di PDF), jadi saldo tetap nyambung walau filter
     * tanggal dimulai di tengah bulan atau ada hari tanpa laporan.
     *
     * @param  string[]  $daftarTanggal  format Y-m-d
     */
    private function susunBukuHarian(array $daftarTanggal): array
    {
        if (empty($daftarTanggal)) {
            return [];
        }

        sort($daftarTanggal);

        $net = $this->nettoPerHari(end($daftarTanggal));

        $masukRinci  = $this->rincianHarian(Pemasukan::class, $daftarTanggal, 'Pendapatan');
        $keluarRinci = $this->rincianHarian(Pengeluaran::class, $daftarTanggal, 'Pengeluaran');

        $hasil = [];

        foreach ($daftarTanggal as $tgl) {
            $saldo = 0;

            foreach ($net as $t => $nilai) {
                if ($t >= $tgl) {
                    break;
                }
                $saldo += $nilai;
            }

            $saldoAwal = $saldo;

            $pendapatan = [];

            foreach ($masukRinci->get($tgl, collect()) as $r) {
                $saldo += $r->total;

                $pendapatan[] = [
                    'label' => $r->label,
                    'debet' => $r->total,
                    'saldo' => $saldo,
                ];
            }

            $pengeluaran = [];

            foreach ($keluarRinci->get($tgl, collect()) as $r) {
                $saldo -= $r->total;

                $pengeluaran[] = [
                    'label'  => $r->label,
                    'kredit' => $r->total,
                    'saldo'  => $saldo,
                ];
            }

            $hasil[] = [
                'tanggal'     => Carbon::parse($tgl),
                'saldo_awal'  => $saldoAwal,
                'pendapatan'  => $pendapatan,
                'pengeluaran' => $pengeluaran,
                'saldo_akhir' => $saldo,
            ];
        }

        return $hasil;
    }

    /**
     * Selisih (pemasukan - pengeluaran) per tanggal, dari awal data sampai $sampai.
     *
     * @return array<string, int>  dikunci Y-m-d, terurut naik
     */
    private function nettoPerHari(string $sampai): array
    {
        $ambil = fn (string $model) => $model::query()
            ->where('status', 'Diterima')
            ->whereDate('tanggal', '<=', $sampai)
            ->selectRaw('DATE(tanggal) as tgl, SUM(nominal) as total')
            ->groupBy('tgl')
            ->pluck('total', 'tgl');

        $net = [];

        foreach ($ambil(Pemasukan::class) as $tgl => $total) {
            $net[$tgl] = ($net[$tgl] ?? 0) + (int) $total;
        }

        foreach ($ambil(Pengeluaran::class) as $tgl => $total) {
            $net[$tgl] = ($net[$tgl] ?? 0) - (int) $total;
        }

        ksort($net);

        return $net;
    }

    /**
     * Rincian per tanggal dan per kategori, dikelompokkan per tanggal.
     * Kolom kategori dicari otomatis dari: kategori, jenis, keterangan, nama.
     * Kalau tidak ada, satu baris per tanggal dengan label bawaan.
     *
     * @return Collection<string, Collection<int, object{tgl: string, label: string, total: int}>>
     */
    private function rincianHarian(string $model, array $daftarTanggal, string $labelKosong): Collection
    {
        $tabel = (new $model)->getTable();

        $kolom = collect(['kategori', 'jenis', 'keterangan', 'nama'])
            ->first(fn ($c) => Schema::hasColumn($tabel, $c));

        $query = $model::query()
            ->where('status', 'Diterima')
            ->whereIn(DB::raw('DATE(tanggal)'), $daftarTanggal);

        if ($kolom) {
            // $kolom berasal dari daftar tetap di atas, aman dipakai langsung.
            $query
                ->selectRaw("DATE(tanggal) as tgl, COALESCE(NULLIF(TRIM({$kolom}), ''), ?) as label, SUM(nominal) as total", [$labelKosong])
                ->groupBy('tgl', 'label');
        } else {
            $query
                ->selectRaw('DATE(tanggal) as tgl, SUM(nominal) as total')
                ->groupBy('tgl');
        }

        return $query
            ->orderBy('tgl')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => (object) [
                'tgl'   => (string) $r->tgl,
                'label' => (string) ($r->label ?? $labelKosong),
                'total' => (int) $r->total,
            ])
            ->groupBy('tgl');
    }
}