<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriPerawatanStoreRequest;
use App\Http\Requests\KategoriPerawatanUpdateRequest;
use App\Models\KategoriPerawatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class KategoriPerawatanController extends Controller
{
    /**
     * =====================================================
     * INDEX
     * =====================================================
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);

        if (!in_array($perPage, [5, 10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = KategoriPerawatan::query()
            ->withCount('perawatans')
            ->orderBy('nama');

        /*
        |--------------------------------------------------------------------------
        | PENCARIAN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('katakunci')) {

            $katakunci = trim($request->katakunci);

            $query->where(function ($q) use ($katakunci) {

                $q->where(
                    'nama',
                    'like',
                    "%{$katakunci}%"
                )->orWhere(
                    'deskripsi',
                    'like',
                    "%{$katakunci}%"
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
        | PAGINATION
        |--------------------------------------------------------------------------
        */
        $data = $query
            ->paginate($perPage)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */
        $totalKategori = KategoriPerawatan::count();

        $kategoriAktif = KategoriPerawatan::where(
            'status',
            'Aktif'
        )->count();

        return view(
            'pages.kategori.index',
            compact(
                'data',
                'totalKategori',
                'kategoriAktif'
            )
        );
    }


    /**
     * =====================================================
     * CREATE
     * =====================================================
     */
    public function create()
    {
        return view(
            'pages.kategori.create'
        );
    }


    /**
     * =====================================================
     * STORE
     * =====================================================
     */
    public function store(
        KategoriPerawatanStoreRequest $request
    ) {
        try {

            KategoriPerawatan::create(
                $request->validated()
            );

            return redirect()
                ->route('kategori.index')
                ->with(
                    'success',
                    'Kategori perawatan berhasil ditambahkan.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambah kategori perawatan',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kategori perawatan gagal ditambahkan.'
                );
        }
    }


    /**
     * =====================================================
     * SHOW / DETAIL
     * =====================================================
     */
    public function show(
        KategoriPerawatan $kategori
    ) {
        $data = $kategori->load([
            'perawatans' => function ($query) {

                $query->orderBy('nama');

            },
        ]);

        return view(
            'pages.kategori.detail',
            compact('data')
        );
    }


    /**
     * =====================================================
     * EDIT
     * =====================================================
     */
    public function edit(
        KategoriPerawatan $kategori
    ) {
        $data = $kategori;

        return view(
            'pages.kategori.edit',
            compact('data')
        );
    }


    /**
     * =====================================================
     * UPDATE
     * =====================================================
     */
    public function update(
        KategoriPerawatanUpdateRequest $request,
        KategoriPerawatan $kategori
    ) {
        try {

            $kategori->update(
                $request->validated()
            );

            return redirect()
                ->route(
                    'kategori.show',
                    [
                        'kategori' => $kategori->id
                    ]
                )
                ->with(
                    'success',
                    'Kategori perawatan berhasil diperbarui.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Gagal mengubah kategori perawatan',
                [
                    'id' => $kategori->id,
                    'message' => $e->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kategori perawatan gagal diperbarui.'
                );
        }
    }


    /**
     * =====================================================
     * DESTROY
     * =====================================================
     */
    public function destroy(
        KategoriPerawatan $kategori
    ) {
        try {

            /*
            |--------------------------------------------------------------------------
            | CEK APAKAH MASIH DIGUNAKAN
            |--------------------------------------------------------------------------
            */
            if (
                $kategori
                    ->perawatans()
                    ->exists()
            ) {

                return redirect()
                    ->route('kategori.index')
                    ->with(
                        'error',
                        'Kategori tidak dapat dihapus karena masih digunakan oleh data perawatan.'
                    );
            }

            $kategori->delete();

            return redirect()
                ->route('kategori.index')
                ->with(
                    'success',
                    'Kategori perawatan berhasil dihapus.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus kategori perawatan',
                [
                    'id' => $kategori->id,
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route('kategori.index')
                ->with(
                    'error',
                    'Kategori perawatan gagal dihapus.'
                );
        }
    }


    /**
     * =====================================================
     * LAPORAN PDF
     * =====================================================
     */
    public function laporanPdf(
        Request $request
    ) {
        $query = KategoriPerawatan::query()
            ->withCount('perawatans')
            ->orderBy('nama');

        /*
        |--------------------------------------------------------------------------
        | FILTER PENCARIAN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('katakunci')) {

            $katakunci = trim($request->katakunci);

            $query->where(function ($q) use ($katakunci) {

                $q->where(
                    'nama',
                    'like',
                    "%{$katakunci}%"
                )->orWhere(
                    'deskripsi',
                    'like',
                    "%{$katakunci}%"
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

        $data = $query->get();

        /*
        |--------------------------------------------------------------------------
        | NOMOR LAPORAN
        |--------------------------------------------------------------------------
        */
        $no_laporan =
            'LKP-' . now()->format('YmdHis');

        $tanggalLaporan =
            now()->translatedFormat(
                'd F Y H:i'
            );

        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */
        $pdf = Pdf::loadView(
            'pages.kategori.laporan_pdf',
            compact(
                'data',
                'no_laporan',
                'tanggalLaporan'
            )
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->stream(
            'laporan-kategori.pdf'
        );
    }
}