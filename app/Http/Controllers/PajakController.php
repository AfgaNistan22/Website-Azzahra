<?php

namespace App\Http\Controllers;

use App\Http\Requests\PajakStoreRequest;
use App\Http\Requests\PajakUpdateRequest;
use App\Models\Pajak;
use App\Models\Pegawai;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PajakController extends Controller
{
    /**
     * Menampilkan daftar pajak.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 5);

        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5;
        }

        $query = Pajak::with('pegawai');

        /*
        |--------------------------------------------------------------------------
        | Pencarian
        |--------------------------------------------------------------------------
        */
        if ($request->filled('katakunci')) {
            $katakunci = $request->katakunci;

            $query->where(function ($q) use ($katakunci) {
                $q->whereHas('pegawai', function ($pegawai) use ($katakunci) {
                    $pegawai->where('nama', 'like', '%' . $katakunci . '%');
                })
                ->orWhere('tahun', 'like', '%' . $katakunci . '%')
                ->orWhere('status', 'like', '%' . $katakunci . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Bulan
        |--------------------------------------------------------------------------
        */
        if ($request->filled('bulan')) {
            $query->where(
                'bulan',
                $request->bulan
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tahun
        |--------------------------------------------------------------------------
        */
        if ($request->filled('tahun')) {
            $query->where(
                'tahun',
                $request->tahun
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $data = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $pegawai = Pegawai::orderBy('nama')->get();

        return view(
            'pages.pajak.index',
            compact(
                'data',
                'pegawai'
            )
        );
    }

    /**
     * Form tambah pajak.
     */
    public function create()
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        return view(
            'pages.pajak.create',
            compact('pegawai')
        );
    }

    /**
     * Simpan data pajak.
     */
    public function store(PajakStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            /*
            |--------------------------------------------------------------------------
            | Hitung PKP
            |--------------------------------------------------------------------------
            */
            $pkp = max(
                (int) $validated['penghasilan_bruto']
                - (int) $validated['ptkp'],
                0
            );

            /*
            |--------------------------------------------------------------------------
            | Hitung jumlah pajak
            |--------------------------------------------------------------------------
            */
            $jumlahPajak = round(
                $pkp
                * ((float) $validated['tarif_pajak'] / 100)
            );

            $validated['pkp'] = $pkp;
            $validated['jumlah_pajak'] = $jumlahPajak;

            /*
            |--------------------------------------------------------------------------
            | Tanggal pembayaran
            |--------------------------------------------------------------------------
            */
            if ($validated['status'] === 'Belum Dibayar') {
                $validated['tanggal_bayar'] = null;
            }

            $pajak = Pajak::create($validated);
            Pengeluaran::syncFromPajak($pajak);

            return redirect()
                ->route('pajak.index')
                ->with(
                    'success',
                    'Data pajak berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {
            Log::error(
                'Gagal menambahkan data pajak: ' .
                $e->getMessage()
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data pajak gagal ditambahkan.'
                );
        }
    }

    /**
     * Menampilkan detail pajak.
     */
    public function show(Pajak $pajak)
    {
        $pajak->load('pegawai');

        return view(
            'pages.pajak.detail',
            [
                'data' => $pajak
            ]
        );
    }

    /**
     * Form edit pajak.
     */
    public function edit(Pajak $pajak)
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        $pajak->load('pegawai');

        return view(
            'pages.pajak.edit',
            [
                'data' => $pajak,
                'pegawai' => $pegawai,
            ]
        );
    }

    /**
     * Update data pajak.
     */
    public function update(
        PajakUpdateRequest $request,
        Pajak $pajak
    ) {
        try {
            $validated = $request->validated();

            /*
            |--------------------------------------------------------------------------
            | Hitung PKP
            |--------------------------------------------------------------------------
            */
            $pkp = max(
                (int) $validated['penghasilan_bruto']
                - (int) $validated['ptkp'],
                0
            );

            /*
            |--------------------------------------------------------------------------
            | Hitung jumlah pajak
            |--------------------------------------------------------------------------
            */
            $jumlahPajak = round(
                $pkp
                * ((float) $validated['tarif_pajak'] / 100)
            );

            $validated['pkp'] = $pkp;
            $validated['jumlah_pajak'] = $jumlahPajak;

            /*
            |--------------------------------------------------------------------------
            | Jika belum dibayar, tanggal bayar dikosongkan.
            |--------------------------------------------------------------------------
            */
            if ($validated['status'] === 'Belum Dibayar') {
                $validated['tanggal_bayar'] = null;
            }

            $pajak->update($validated);
            Pengeluaran::syncFromPajak($pajak->refresh());

            return redirect()
                ->route(
                    'pajak.show',
                    $pajak
                )
                ->with(
                    'success',
                    'Data pajak berhasil diperbarui.'
                );
        } catch (\Throwable $e) {
            Log::error(
                'Gagal memperbarui data pajak: ' .
                $e->getMessage()
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data pajak gagal diperbarui.'
                );
        }
    }

    /**
     * Hapus data pajak.
     */
    public function destroy(Pajak $pajak)
    {
        try {
            $pajak->delete();

            return redirect()
                ->route('pajak.index')
                ->with(
                    'success',
                    'Data pajak berhasil dihapus.'
                );
        } catch (\Throwable $e) {
            Log::error(
                'Gagal menghapus data pajak: ' .
                $e->getMessage()
            );

            return back()
                ->with(
                    'error',
                    'Data pajak gagal dihapus.'
                );
        }
    }

    /**
     * Laporan PDF.
     */
    public function laporanPdf(Request $request)
    {
        $query = Pajak::with('pegawai');

        /*
        |--------------------------------------------------------------------------
        | Filter pencarian
        |--------------------------------------------------------------------------
        */
        if ($request->filled('katakunci')) {
            $katakunci = $request->katakunci;

            $query->where(function ($q) use ($katakunci) {
                $q->whereHas('pegawai', function ($pegawai) use ($katakunci) {
                    $pegawai->where(
                        'nama',
                        'like',
                        '%' . $katakunci . '%'
                    );
                })
                ->orWhere(
                    'tahun',
                    'like',
                    '%' . $katakunci . '%'
                )
                ->orWhere(
                    'status',
                    'like',
                    '%' . $katakunci . '%'
                );
            });
        }

        if ($request->filled('bulan')) {
            $query->where(
                'bulan',
                $request->bulan
            );
        }

        if ($request->filled('tahun')) {
            $query->where(
                'tahun',
                $request->tahun
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $data = $query
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $pdf = Pdf::loadView(
            'pages.pajak.laporan_pdf',
            compact('data')
        );

        $pdf->setPaper(
            'A4',
            'landscape'
        );

        return $pdf->stream(
            'laporan-pajak.pdf'
        );
    }
}