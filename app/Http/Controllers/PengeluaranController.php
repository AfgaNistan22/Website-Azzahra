<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengeluaranStoreRequest;
use App\Http\Requests\PengeluaranUpdateRequest;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);

        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = Pengeluaran::with(['gaji.pegawai', 'pajak.pegawai'])
            ->latest('tanggal')
            ->latest('id');

        if ($request->filled('katakunci')) {
            $katakunci = $request->katakunci;

            $query->where(function ($q) use ($katakunci) {
                $q->where('kode_pengeluaran', 'like', '%' . $katakunci . '%')
                    ->orWhere('kategori', 'like', '%' . $katakunci . '%')
                    ->orWhere('sumber', 'like', '%' . $katakunci . '%')
                    ->orWhere('keterangan', 'like', '%' . $katakunci . '%');
            });
        }

        if ($request->filled('sumber_jenis')) {
            $query->where('sumber_jenis', $request->sumber_jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $data = $query->paginate($perPage)->withQueryString();

        $totalDiterima = (clone $query)
            ->where('status', 'Diterima')
            ->sum('nominal');

        $totalManual = (clone $query)
            ->where('status', 'Diterima')
            ->where('sumber_jenis', 'Manual')
            ->sum('nominal');

        $totalGaji = (clone $query)
            ->where('status', 'Diterima')
            ->where('sumber_jenis', 'Gaji')
            ->sum('nominal');

        $totalPajak = (clone $query)
            ->where('status', 'Diterima')
            ->where('sumber_jenis', 'Pajak')
            ->sum('nominal');

        return view('pages.pengeluaran.index', compact(
            'data',
            'totalDiterima',
            'totalManual',
            'totalGaji',
            'totalPajak'
        ));
    }

    public function create()
    {
        return view('pages.pengeluaran.create');
    }

    public function store(PengeluaranStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $pengeluaran = Pengeluaran::create(array_merge(
                $validated,
                [
                    'kode_pengeluaran' => Pengeluaran::generateKode(),
                    'sumber_jenis' => 'Manual',
                    'gaji_id' => null,
                    'pajak_id' => null,
                ]
            ));

            return redirect()
                ->route('pengeluaran.show', $pengeluaran->id)
                ->with('success', 'Pengeluaran manual berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Pengeluaran store error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Pengeluaran gagal ditambahkan: ' . $e->getMessage());
        }
    }

    public function show(Pengeluaran $pengeluaran)
    {
        $pengeluaran->load(['gaji.pegawai', 'pajak.pegawai']);

        $data = $pengeluaran;

        return view('pages.pengeluaran.detail', compact('data'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->isOtomatis()) {
            return redirect()
                ->route('pengeluaran.show', $pengeluaran->id)
                ->with('error', 'Pengeluaran dari Gaji/Pajak tidak dapat diedit dari menu Pengeluaran.');
        }

        $data = $pengeluaran;

        return view('pages.pengeluaran.edit', compact('data'));
    }

    public function update(
        PengeluaranUpdateRequest $request,
        Pengeluaran $pengeluaran
    ) {
        if ($pengeluaran->isOtomatis()) {
            return redirect()
                ->route('pengeluaran.show', $pengeluaran->id)
                ->with('error', 'Pengeluaran dari Gaji/Pajak tidak dapat diedit dari menu Pengeluaran.');
        }

        try {
            $pengeluaran->update($request->validated());

            return redirect()
                ->route('pengeluaran.show', $pengeluaran->id)
                ->with('success', 'Pengeluaran manual berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Pengeluaran update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Pengeluaran gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->isOtomatis()) {
            return redirect()
                ->route('pengeluaran.show', $pengeluaran->id)
                ->with('error', 'Pengeluaran dari Gaji/Pajak tidak dapat dihapus dari menu Pengeluaran.');
        }

        try {
            $pengeluaran->delete();

            return redirect()
                ->route('pengeluaran.index')
                ->with('success', 'Pengeluaran manual berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Pengeluaran destroy error: ' . $e->getMessage());

            return back()
                ->with('error', 'Pengeluaran gagal dihapus: ' . $e->getMessage());
        }
    }

    public function laporanPdf(Request $request)
    {
        $query = Pengeluaran::with(['gaji.pegawai', 'pajak.pegawai'])
            ->latest('tanggal')
            ->latest('id');

        if ($request->filled('katakunci')) {
            $katakunci = $request->katakunci;

            $query->where(function ($q) use ($katakunci) {
                $q->where('kode_pengeluaran', 'like', '%' . $katakunci . '%')
                    ->orWhere('kategori', 'like', '%' . $katakunci . '%')
                    ->orWhere('sumber', 'like', '%' . $katakunci . '%')
                    ->orWhere('keterangan', 'like', '%' . $katakunci . '%');
            });
        }

        if ($request->filled('sumber_jenis')) {
            $query->where('sumber_jenis', $request->sumber_jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $data = $query->get();

        $totalDiterima = $data
            ->where('status', 'Diterima')
            ->sum('nominal');

        $pdf = Pdf::loadView(
            'pages.pengeluaran.laporan_pdf',
            compact('data', 'totalDiterima')
        )->setPaper('a4', 'landscape');

        return $pdf->stream(
            'laporan-pengeluaran-' . now()->format('YmdHis') . '.pdf'
        );
    }
}
