<?php

namespace App\Http\Controllers;

use App\Http\Requests\GajiStoreRequest;
use App\Http\Requests\GajiUpdateRequest;
use App\Models\Gaji;
use App\Models\Pegawai;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $katakunci = trim((string) $request->input('katakunci'));
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $status = $request->input('status');

            $perPage = (int) $request->input('per_page', 10);

            if (!in_array($perPage, [5, 10, 25, 50, 100])) {
                $perPage = 10;
            }

            $query = Gaji::with('pegawai');

            if ($katakunci !== '') {
                $query->whereHas('pegawai', function ($q) use ($katakunci) {
                    $q->where('nama', 'like', '%' . $katakunci . '%');
                });
            }

            if ($bulan !== null && $bulan !== '') {
                $query->where('bulan', (int) $bulan);
            }

            if ($tahun !== null && $tahun !== '') {
                $query->where('tahun', (int) $tahun);
            }

            if ($status !== null && $status !== '') {
                $query->where('status', $status);
            }

            $data = $query
                ->orderByDesc('tahun')
                ->orderByDesc('bulan')
                ->orderBy('pegawai_id')
                ->paginate($perPage)
                ->withQueryString();

            $totalGaji = Gaji::sum('total_gaji');
            $gajiDibayar = Gaji::where('status', 'Dibayar')->sum('total_gaji');
            $gajiBelumDibayar = Gaji::where('status', 'Belum Dibayar')->sum('total_gaji');
            $jumlahData = Gaji::count();

            $tahunList = Gaji::select('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun');

            return view(
                'pages.gaji.index',
                compact(
                    'data',
                    'totalGaji',
                    'gajiDibayar',
                    'gajiBelumDibayar',
                    'jumlahData',
                    'tahunList'
                )
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal mengambil data gaji',
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return back()->with(
                'error',
                'Terjadi kesalahan saat mengambil data gaji.'
            );
        }
    }

    public function create()
    {
        try {
            $pegawai = Pegawai::where('status', 'Aktif')
                ->orderBy('nama')
                ->get();

            return view(
                'pages.gaji.create',
                compact('pegawai')
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal membuka form tambah gaji',
                ['message' => $e->getMessage()]
            );

            return redirect()
                ->route('gaji.index')
                ->with(
                    'error',
                    'Form tambah gaji tidak dapat dibuka.'
                );
        }
    }

    public function store(GajiStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $sudahAda = Gaji::where(
                'pegawai_id',
                $validated['pegawai_id']
            )
                ->where('bulan', $validated['bulan'])
                ->where('tahun', $validated['tahun'])
                ->exists();

            if ($sudahAda) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Data gaji untuk pegawai dan periode tersebut sudah tersedia.'
                    );
            }

            $gajiPokok = (int) $validated['gaji_pokok'];
            $tunjangan = (int) ($validated['tunjangan'] ?? 0);
            $potongan = (int) ($validated['potongan'] ?? 0);

            if ($potongan > ($gajiPokok + $tunjangan)) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Potongan tidak boleh lebih besar dari total pendapatan.'
                    );
            }

            $validated['tunjangan'] = $tunjangan;
            $validated['potongan'] = $potongan;
            $validated['total_gaji'] =
                $gajiPokok + $tunjangan - $potongan;

            if ($validated['status'] !== 'Dibayar') {
                $validated['tanggal_bayar'] = null;
            }

            $gaji = Gaji::create($validated);
            Pengeluaran::syncFromGaji($gaji);

            return redirect()
                ->route('gaji.index')
                ->with(
                    'success',
                    'Data gaji berhasil ditambahkan.'
                );
        } catch (Throwable $e) {
            Log::error(
                'Gagal menyimpan data gaji',
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data gaji gagal ditambahkan.'
                );
        }
    }

    public function show(Gaji $gaji)
    {
        try {
            $gaji->load('pegawai');

            $data = $gaji;

            return view(
                'pages.gaji.detail',
                compact('data')
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal menampilkan detail gaji',
                [
                    'id' => $gaji->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route('gaji.index')
                ->with(
                    'error',
                    'Data gaji tidak ditemukan.'
                );
        }
    }

    public function edit(Gaji $gaji)
    {
        try {
            $pegawai = Pegawai::where('status', 'Aktif')
                ->orWhere('id', $gaji->pegawai_id)
                ->orderBy('nama')
                ->get();

            $data = $gaji;

            return view(
                'pages.gaji.edit',
                compact('data', 'pegawai')
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal membuka edit gaji',
                [
                    'id' => $gaji->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route('gaji.index')
                ->with(
                    'error',
                    'Form edit gaji tidak dapat dibuka.'
                );
        }
    }

    public function update(
        GajiUpdateRequest $request,
        Gaji $gaji
    ) {
        try {
            $validated = $request->validated();

            $sudahAda = Gaji::where(
                'pegawai_id',
                $validated['pegawai_id']
            )
                ->where('bulan', $validated['bulan'])
                ->where('tahun', $validated['tahun'])
                ->where('id', '!=', $gaji->id)
                ->exists();

            if ($sudahAda) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Data gaji untuk pegawai dan periode tersebut sudah tersedia.'
                    );
            }

            $gajiPokok = (int) $validated['gaji_pokok'];
            $tunjangan = (int) ($validated['tunjangan'] ?? 0);
            $potongan = (int) ($validated['potongan'] ?? 0);

            if ($potongan > ($gajiPokok + $tunjangan)) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Potongan tidak boleh lebih besar dari total pendapatan.'
                    );
            }

            $validated['tunjangan'] = $tunjangan;
            $validated['potongan'] = $potongan;
            $validated['total_gaji'] =
                $gajiPokok + $tunjangan - $potongan;

            if ($validated['status'] !== 'Dibayar') {
                $validated['tanggal_bayar'] = null;
            }

            $gaji->update($validated);
            Pengeluaran::syncFromGaji($gaji->refresh());

            return redirect()
                ->route('gaji.show', $gaji->id)
                ->with(
                    'success',
                    'Data gaji berhasil diperbarui.'
                );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui data gaji',
                [
                    'id' => $gaji->id ?? null,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data gaji gagal diperbarui.'
                );
        }
    }

    public function destroy(Gaji $gaji)
    {
        try {
            $gaji->delete();

            return redirect()
                ->route('gaji.index')
                ->with(
                    'success',
                    'Data gaji berhasil dihapus.'
                );
        } catch (Throwable $e) {
            Log::error(
                'Gagal menghapus data gaji',
                [
                    'id' => $gaji->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                'Data gaji gagal dihapus.'
            );
        }
    }

    public function laporanPdf(Request $request)
    {
        try {
            $katakunci = trim((string) $request->input('katakunci'));
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $status = $request->input('status');

            $query = Gaji::with('pegawai');

            if ($katakunci !== '') {
                $query->whereHas('pegawai', function ($q) use ($katakunci) {
                    $q->where('nama', 'like', '%' . $katakunci . '%');
                });
            }

            if ($bulan !== null && $bulan !== '') {
                $query->where('bulan', (int) $bulan);
            }

            if ($tahun !== null && $tahun !== '') {
                $query->where('tahun', (int) $tahun);
            }

            if ($status !== null && $status !== '') {
                $query->where('status', $status);
            }

            $data = $query
                ->orderByDesc('tahun')
                ->orderByDesc('bulan')
                ->orderBy('pegawai_id')
                ->get();

            $total = $data->sum('total_gaji');

            $pdf = Pdf::loadView(
                'pages.gaji.laporan_pdf',
                compact('data', 'total')
            );

            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream(
                'laporan-gaji.pdf'
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal membuat laporan PDF gaji',
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return back()->with(
                'error',
                'Laporan gaji gagal dibuat.'
            );
        }
    }
}
