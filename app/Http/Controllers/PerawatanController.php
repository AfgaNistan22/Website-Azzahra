<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerawatanStoreRequest;
use App\Http\Requests\PerawatanUpdateRequest;
use App\Models\KategoriPerawatan;
use App\Models\Perawatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PerawatanController extends Controller
{
    /**
     * ============================
     * INDEX
     * ============================
     */
    public function index(Request $request)
    {
        // ============================
        // PAGINATION
        // ============================
        $perPage = (int) $request->input('per_page', 5);

        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5;
        }


        // ============================
        // QUERY PERAWATAN
        // ============================
        $query = Perawatan::with('kategori');


        // ============================
        // SEARCH
        // ============================
        if ($request->filled('katakunci')) {

            $katakunci = trim($request->katakunci);

            $query->where(function ($q) use ($katakunci) {

                $q->where('nama', 'like', '%' . $katakunci . '%')

                    ->orWhere('deskripsi', 'like', '%' . $katakunci . '%')

                    ->orWhereHas('kategori', function ($qKategori) use ($katakunci) {

                        $qKategori->where(
                            'nama',
                            'like',
                            '%' . $katakunci . '%'
                        );

                    });

            });
        }


        // ============================
        // FILTER KATEGORI
        // ============================
        if ($request->filled('kategori_id')) {

            $query->where(
                'kategori_id',
                $request->kategori_id
            );
        }


        // ============================
        // DATA PERAWATAN
        // ============================
        $data = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();


        // ============================
        // DATA KATEGORI
        // ============================
        $kategori = KategoriPerawatan::where(
                'status',
                'Aktif'
            )
            ->orderBy('nama', 'asc')
            ->get();


        // ============================
        // RETURN VIEW
        // ============================
        return view(
            'pages.perawatan.index',
            compact(
                'data',
                'kategori'
            )
        );
    }


    /**
     * ============================
     * CREATE
     * ============================
     */
    public function create()
    {
        $kategori = KategoriPerawatan::where(
                'status',
                'Aktif'
            )
            ->orderBy('nama', 'asc')
            ->get();


        return view(
            'pages.perawatan.create',
            compact('kategori')
        );
    }


    /**
     * ============================
     * STORE
     * ============================
     */
    public function store(PerawatanStoreRequest $request)
    {
        try {

            $validated = $request->validated();


            // ============================
            // UPLOAD FOTO
            // ============================
            if ($request->hasFile('foto')) {

                $file = $request->file('foto');

                $namaFoto = time()
                    . '_'
                    . uniqid()
                    . '.'
                    . $file->getClientOriginalExtension();

                $file->storeAs(
                    'foto_perawatan',
                    $namaFoto,
                    'public'
                );

                $validated['foto'] = $namaFoto;
            }


            // ============================
            // SIMPAN DATA
            // ============================
            Perawatan::create($validated);


            return redirect()
                ->route('perawatan.index')
                ->with(
                    'success',
                    'Data perawatan berhasil ditambahkan.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan data perawatan',
                [
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]
            );


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data perawatan gagal ditambahkan.'
                );
        }
    }


    /**
     * ============================
     * SHOW / DETAIL
     * ============================
     */
    public function show(Perawatan $perawatan)
    {
        try {

            // Load relasi kategori
            $perawatan->load('kategori');

            $data = $perawatan;


            return view(
                'pages.perawatan.detail',
                compact('data')
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menampilkan detail perawatan',
                [
                    'id'      => $perawatan->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );


            return redirect()
                ->route('perawatan.index')
                ->with(
                    'error',
                    'Data perawatan tidak dapat ditampilkan.'
                );
        }
    }


    /**
     * ============================
     * EDIT
     * ============================
     */
    public function edit(Perawatan $perawatan)
    {
        try {

            // ============================
            // DATA KATEGORI AKTIF
            // ============================
            $kategori = KategoriPerawatan::where(
                    'status',
                    'Aktif'
                )
                ->orderBy('nama', 'asc')
                ->get();


            // ============================
            // DATA PERAWATAN
            // ============================
            $data = $perawatan;


            return view(
                'pages.perawatan.edit',
                compact(
                    'data',
                    'kategori'
                )
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal membuka halaman edit perawatan',
                [
                    'id'      => $perawatan->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );


            return redirect()
                ->route('perawatan.index')
                ->with(
                    'error',
                    'Halaman edit perawatan tidak dapat dibuka.'
                );
        }
    }


    /**
     * ============================
     * UPDATE
     * ============================
     */
    public function update(
        PerawatanUpdateRequest $request,
        Perawatan $perawatan
    ) {
        try {

            $validated = $request->validated();


            // ============================
            // FOTO BARU
            // ============================
            if ($request->hasFile('foto')) {

                // Hapus foto lama
                if (
                    $perawatan->foto &&
                    Storage::disk('public')->exists(
                        'foto_perawatan/' . $perawatan->foto
                    )
                ) {

                    Storage::disk('public')->delete(
                        'foto_perawatan/' . $perawatan->foto
                    );
                }


                // Upload foto baru
                $file = $request->file('foto');

                $namaFoto = time()
                    . '_'
                    . uniqid()
                    . '.'
                    . $file->getClientOriginalExtension();


                $file->storeAs(
                    'foto_perawatan',
                    $namaFoto,
                    'public'
                );


                $validated['foto'] = $namaFoto;
            }


            // ============================
            // UPDATE DATA
            // ============================
            $perawatan->update($validated);


            return redirect()
                ->route(
                    'perawatan.show',
                    $perawatan->id
                )
                ->with(
                    'success',
                    'Data perawatan berhasil diperbarui.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui data perawatan',
                [
                    'id'      => $perawatan->id ?? null,
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]
            );


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data perawatan gagal diperbarui.'
                );
        }
    }


    /**
     * ============================
     * DELETE
     * ============================
     */
    public function destroy(Perawatan $perawatan)
    {
        try {

            // ============================
            // HAPUS FOTO
            // ============================
            if (
                $perawatan->foto &&
                Storage::disk('public')->exists(
                    'foto_perawatan/' . $perawatan->foto
                )
            ) {

                Storage::disk('public')->delete(
                    'foto_perawatan/' . $perawatan->foto
                );
            }


            // ============================
            // HAPUS DATA
            // ============================
            $perawatan->delete();


            return redirect()
                ->route('perawatan.index')
                ->with(
                    'success',
                    'Data perawatan berhasil dihapus.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus data perawatan',
                [
                    'id'      => $perawatan->id ?? null,
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]
            );


            return redirect()
                ->route('perawatan.index')
                ->with(
                    'error',
                    'Data perawatan gagal dihapus.'
                );
        }
    }


    /**
     * ============================
     * LAPORAN PDF
     * ============================
     */
    public function laporanPdf(Request $request)
    {
        try {

            // ============================
            // QUERY
            // ============================
            $query = Perawatan::with('kategori');


            // ============================
            // SEARCH
            // ============================
            if ($request->filled('katakunci')) {

                $katakunci = trim(
                    $request->katakunci
                );


                $query->where(function ($q) use ($katakunci) {

                    $q->where(
                        'nama',
                        'like',
                        '%' . $katakunci . '%'
                    )

                    ->orWhere(
                        'deskripsi',
                        'like',
                        '%' . $katakunci . '%'
                    )

                    ->orWhereHas(
                        'kategori',
                        function ($qKategori) use ($katakunci) {

                            $qKategori->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );

                        }
                    );

                });
            }


            // ============================
            // FILTER KATEGORI
            // ============================
            if ($request->filled('kategori_id')) {

                $query->where(
                    'kategori_id',
                    $request->kategori_id
                );
            }


            // ============================
            // DATA
            // ============================
            $data = $query
                ->orderBy('nama', 'asc')
                ->get();


            // ============================
            // PDF
            // ============================
            $pdf = Pdf::loadView(
                'pages.perawatan.laporan_pdf',
                compact('data')
            );


            $pdf->setPaper(
                'A4',
                'portrait'
            );


            return $pdf->stream(
                'laporan-perawatan.pdf'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal membuat laporan PDF perawatan',
                [
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]
            );


            return back()
                ->with(
                    'error',
                    'Laporan PDF gagal dibuat.'
                );
        }
    }
}