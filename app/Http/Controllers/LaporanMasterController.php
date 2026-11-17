<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanMasterStoreRequest;
use App\Http\Requests\LaporanMasterUpdateRequest;
use App\Models\LaporanMaster;
use App\Services\LaporanMasterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class LaporanMasterController extends Controller
{
    private const MAKS_BARIS_PDF   = 1500;
    private const MAKS_LAPORAN_PDF = 10;

    public function __construct(private LaporanMasterService $service)
    {
    }

    /**
     * Daftar laporan master + ringkasan.
     * Kategori yang belum punya laporan dibuatkan otomatis (Draft, semua data).
     */
    public function index(Request $request)
    {
        try {
            // Sinkron otomatis: gagal sinkron tidak boleh membuat halaman error
            try {
                $this->service->sinkronkan();
            } catch (Throwable $e) {
                Log::warning('Sinkron otomatis laporan master gagal.', ['message' => $e->getMessage()]);
            }

            $perPage = (int) $request->input('per_page', 10);

            if (!in_array($perPage, [5, 10, 25, 50, 100], true)) {
                $perPage = 10;
            }

            $query = $this->filterLaporan($request);

            $jumlahLaporan  = (clone $query)->count();
            $perStatus      = (clone $query)->selectRaw('status, COUNT(*) as n')->groupBy('status')->pluck('n', 'status');
            $jumlahDraft    = (int) ($perStatus['Draft'] ?? 0);
            $jumlahFinal    = (int) ($perStatus['Final'] ?? 0);
            $jumlahKategori = (clone $query)->distinct()->count('kategori');

            $kategoriList = $this->service->daftarKategori();

            $data = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            $data->getCollection()->each(fn ($l) => $this->terapkanLive($l, $kategoriList));

            return view('pages.laporan.master.index', compact(
                'data',
                'kategoriList',
                'jumlahLaporan',
                'jumlahDraft',
                'jumlahFinal',
                'jumlahKategori'
            ));
        } catch (Throwable $e) {
            Log::error('Gagal menampilkan laporan master.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Laporan master gagal ditampilkan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $kategoriForm = $this->kategoriUntukForm();

        if (empty($kategoriForm)) {
            return redirect()->route('master.index')
                ->with('error', 'Belum ada kategori laporan yang terdaftar. Periksa daftarKategori() di LaporanMasterService.');
        }

        return view('pages.laporan.master.create', compact('kategoriForm'));
    }

    /** Preview jumlah data dan total nominal (AJAX di create/edit). */
    public function preview(Request $request)
    {
        try {
            $request->validate([
                'kategori'        => ['required', 'in:' . implode(',', array_keys($this->service->daftarKategori()))],
                'tanggal_mulai'   => ['nullable', 'date'],
                'tanggal_selesai' => ['nullable', 'date'],
            ]);

            return response()->json(array_merge(
                ['success' => true],
                $this->service->ringkasan(
                    $request->kategori,
                    $request->input('tanggal_mulai'),
                    $request->input('tanggal_selesai')
                )
            ));
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Gagal preview laporan master.', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simpan laporan baru (manual).
     * Satu kategori + rentang tanggal = satu laporan. Duplikat sudah ditolak StoreRequest.
     */
    public function store(LaporanMasterStoreRequest $request)
    {
        try {
            LaporanMaster::create(array_merge(
                ['kode_laporan' => LaporanMaster::generateKode()],
                $this->atributSimpan($request->validated())
            ));

            return redirect()
                ->route('master.index')
                ->with('success', 'Laporan master berhasil disimpan.');
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan laporan master.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Laporan master gagal disimpan: ' . $e->getMessage());
        }
    }

    /** Detail: seluruh data kategori ditampilkan live dari tabel aslinya. */
    public function show(Request $request, LaporanMaster $laporan_master)
    {
        try {
            $kat       = $this->service->kategori($laporan_master->kategori);
            $kolom     = [];
            $baris     = null;
            $totalUang = [];
            $perPage   = (int) $request->input('per_page', 10);

            if (!in_array($perPage, [10, 25, 50, 100], true)) {
                $perPage = 10;
            }

            if ($kat) {
                $meta  = $this->service->meta($kat);
                $query = $this->service->query(
                    $kat,
                    $meta,
                    $laporan_master->tanggal_mulai?->toDateString(),
                    $laporan_master->tanggal_selesai?->toDateString()
                );

                $uang = $this->service->totalUang($query, $meta);

                $baris = $this->service->urutkan(clone $query, $kat, $meta)
                    ->paginate($perPage)
                    ->withQueryString();

                $baris->getCollection()->transform(
                    fn ($row) => $this->service->barisUntukTampil($row, $meta['kolom'])
                );

                $kolom = $meta['kolom'];

                foreach ($uang as $key => $nilai) {
                    $totalUang[] = [
                        'label' => 'Total ' . $meta['kolom'][$key],
                        'nilai' => 'Rp ' . number_format($nilai, 0, ',', '.'),
                    ];
                }

                $pertama = array_key_first($uang);

                $laporan_master->setAttribute('jumlah_data', $baris->total());
                $laporan_master->setAttribute('kolom_nominal', $pertama ? $meta['kolom'][$pertama] : null);
                $laporan_master->setAttribute('total_nominal', $pertama ? (int) round($uang[$pertama]) : 0);
            }

            return view('pages.laporan.master.detail', [
                'data'      => $laporan_master,
                'kat'       => $kat,
                'kolom'     => $kolom,
                'baris'     => $baris,
                'totalUang' => $totalUang,
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menampilkan detail laporan master.', ['message' => $e->getMessage()]);

            return back()->with('error', 'Detail laporan master gagal ditampilkan: ' . $e->getMessage());
        }
    }

    public function edit(LaporanMaster $laporan_master)
    {
        return view('pages.laporan.master.edit', [
            'data'         => $laporan_master,
            'kategoriForm' => $this->kategoriUntukForm(),
        ]);
    }

    public function update(LaporanMasterUpdateRequest $request, LaporanMaster $laporan_master)
    {
        try {
            $laporan_master->update($this->atributSimpan($request->validated()));

            return redirect()
                ->route('master.show', $laporan_master->id)
                ->with('success', 'Laporan master berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('Gagal mengupdate laporan master.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
                'id'      => $laporan_master->id ?? null,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Laporan master gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(LaporanMaster $laporan_master)
    {
        try {
            $laporan_master->delete();

            return redirect()
                ->route('master.index')
                ->with('success', 'Laporan master berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus laporan master.', [
                'message' => $e->getMessage(),
                'id'      => $laporan_master->id ?? null,
            ]);

            return back()->with('error', 'Laporan master gagal dihapus: ' . $e->getMessage());
        }
    }

    /** PDF: tiap laporan menjadi satu bagian berisi seluruh data kategorinya. */
    public function laporanPdf(Request $request)
    {
        try {
            $jumlahTerfilter = $this->filterLaporan($request)->count();

            $laporan = $this->filterLaporan($request)
                ->orderByDesc('id')
                ->limit(self::MAKS_LAPORAN_PDF)
                ->get();

            $bagian    = [];
            $landscape = false;

            foreach ($laporan as $l) {
                $kat = $this->service->kategori($l->kategori);

                if (!$kat) {
                    $bagian[] = ['laporan' => $l, 'kat' => null];
                    continue;
                }

                $meta  = $this->service->meta($kat);
                $query = $this->service->query(
                    $kat,
                    $meta,
                    $l->tanggal_mulai?->toDateString(),
                    $l->tanggal_selesai?->toDateString()
                );

                $totalData = (clone $query)->count();
                $uang      = $this->service->totalUang($query, $meta);

                $baris = $this->service->urutkan(clone $query, $kat, $meta)
                    ->limit(self::MAKS_BARIS_PDF)
                    ->get()
                    ->map(fn ($row) => $this->service->barisUntukTampil($row, $meta['kolom']))
                    ->all();

                $totalKolom = [];

                foreach ($uang as $key => $nilai) {
                    $totalKolom[$key] = 'Rp ' . number_format($nilai, 0, ',', '.');
                }

                if (count($meta['kolom']) > 6) {
                    $landscape = true;
                }

                $bagian[] = [
                    'laporan'    => $l,
                    'kat'        => $kat,
                    'kolom'      => $meta['kolom'],
                    'baris'      => $baris,
                    'totalKolom' => $totalKolom,
                    'totalData'  => $totalData,
                    'terpotong'  => $totalData > self::MAKS_BARIS_PDF,
                ];
            }

            $pdf = Pdf::loadView('pages.laporan.master.laporan_pdf', [
                'bagian'           => $bagian,
                'maksBaris'        => self::MAKS_BARIS_PDF,
                'laporanTerpotong' => $jumlahTerfilter > self::MAKS_LAPORAN_PDF,
                'jumlahTerfilter'  => $jumlahTerfilter,
                'maksLaporan'      => self::MAKS_LAPORAN_PDF,
            ]);

            $pdf->setPaper('A4', $landscape ? 'landscape' : 'portrait');

            return $pdf->stream('laporan-master-' . now()->format('YmdHis') . '.pdf');
        } catch (Throwable $e) {
            Log::error('Gagal membuat PDF laporan master.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Laporan PDF gagal dibuat: ' . $e->getMessage());
        }
    }

    /* ======================== HELPER ======================== */

    private function filterLaporan(Request $request): Builder
    {
        $query = LaporanMaster::query();

        if ($request->filled('katakunci')) {
            $kata = trim($request->katakunci);

            $query->where(function ($q) use ($kata) {
                $q->where('kode_laporan', 'like', "%{$kata}%")
                  ->orWhere('catatan', 'like', "%{$kata}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    /** Atribut yang disimpan: input form + angka hasil hitung otomatis. */
    private function atributSimpan(array $v): array
    {
        $adaTanggal = $this->service->adaKolomTanggal($v['kategori']);

        $mulai   = $adaTanggal ? ($v['tanggal_mulai'] ?? null) : null;
        $selesai = $adaTanggal ? ($v['tanggal_selesai'] ?? null) : null;

        $ringkas = $this->service->ringkasan($v['kategori'], $mulai, $selesai);

        return [
            'kategori'        => $v['kategori'],
            'tanggal_mulai'   => $mulai,
            'tanggal_selesai' => $selesai,
            'status'          => $v['status'],
            'catatan'         => $v['catatan'] ?? null,
            'jumlah_data'     => $ringkas['jumlah_data'],
            'kolom_nominal'   => $ringkas['kolom_nominal'],
            'total_nominal'   => $ringkas['total_nominal'],
        ];
    }

    /** Data kategori untuk dropdown form + info apakah punya filter tanggal. */
    private function kategoriUntukForm(): array
    {
        $hasil = [];

        foreach ($this->service->daftarKategori() as $kunci => $kat) {
            $hasil[$kunci] = [
                'label'       => $kat['label'],
                'ikon'        => $kat['ikon'] ?? 'fa-folder',
                'ada_tanggal' => (bool) $this->service->meta($kat)['kolomTanggal'],
            ];
        }

        return $hasil;
    }

    /** Tempel label kategori dan angka live ke model (hanya tampilan, tidak disimpan). */
    private function terapkanLive(LaporanMaster $l, array $kategoriList): void
    {
        $kat = $kategoriList[$l->kategori] ?? null;

        $l->setAttribute('kategori_label', $kat['label'] ?? Str::headline($l->kategori));
        $l->setAttribute('kategori_ikon', $kat['ikon'] ?? 'fa-folder');
        $l->setAttribute('kategori_ada', (bool) $kat);

        if (!$kat) {
            return;
        }

        try {
            $r = $this->service->ringkasan(
                $l->kategori,
                $l->tanggal_mulai?->toDateString(),
                $l->tanggal_selesai?->toDateString()
            );

            foreach (['jumlah_data', 'kolom_nominal', 'total_nominal'] as $k) {
                $l->setAttribute($k, $r[$k]);
            }
        } catch (Throwable $e) {
            Log::warning('Gagal menghitung ulang laporan master.', [
                'id'      => $l->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}