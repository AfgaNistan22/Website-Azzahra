<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Http\Requests\CabangStoreRequest;
use App\Http\Requests\CabangUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CabangController extends Controller
{
    /**
     * =====================================================
     * INDEX
     * =====================================================
     */
    public function index(Request $request)
    {
        try {

            $katakunci = $request->katakunci;

            $perPage = $request->input('per_page', 10);

            $data = Cabang::query()

                ->when($katakunci, function ($query, $katakunci) {

                    $query->where(function ($q) use ($katakunci) {

                        $q->where(
                            'nama_cabang',
                            'like',
                            "%{$katakunci}%"
                        )

                        ->orWhere(
                            'kode_cabang',
                            'like',
                            "%{$katakunci}%"
                        )

                        ->orWhere(
                            'alamat_cabang',
                            'like',
                            "%{$katakunci}%"
                        )

                        ->orWhere(
                            'no_telepon',
                            'like',
                            "%{$katakunci}%"
                        )

                        ->orWhere(
                            'email',
                            'like',
                            "%{$katakunci}%"
                        );

                    });

                })

                ->orderBy('kode_cabang')

                ->paginate($perPage)

                ->withQueryString();


            $totalCabang = Cabang::count();


            return view(
                'pages.cabang.index',
                compact(
                    'data',
                    'totalCabang'
                )
            );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@index: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memuat data cabang.'
                );
        }
    }


    /**
     * =====================================================
     * CREATE
     * =====================================================
     */
    public function create()
    {
        try {

            return view(
                'pages.cabang.create'
            );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@create: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('cabang.index')
                ->with(
                    'error',
                    'Form tambah cabang tidak dapat dibuka.'
                );
        }
    }


    /**
     * =====================================================
     * STORE
     * =====================================================
     */
    public function store(CabangStoreRequest $request)
    {
        try {

            /*
             * Ambil data yang sudah lolos validasi
             */
            $data = $request->validated();


            /*
             * Simpan data cabang
             */
            Cabang::create($data);


            /*
             * Kembali ke halaman index
             */
            return redirect()
                ->route('cabang.index')
                ->with(
                    'success',
                    'Data cabang berhasil ditambahkan.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@store: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyimpan data cabang.'
                );
        }
    }


    /**
     * =====================================================
     * SHOW / DETAIL
     * =====================================================
     */
    public function show($id)
    {
        try {

            $data = Cabang::findOrFail($id);


            return view(
                'pages.cabang.detail',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@show: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('cabang.index')
                ->with(
                    'error',
                    'Data cabang tidak ditemukan.'
                );
        }
    }


    /**
     * =====================================================
     * EDIT
     * =====================================================
     */
    public function edit($id)
    {
        try {

            $data = Cabang::findOrFail($id);


            return view(
                'pages.cabang.edit',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@edit: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('cabang.index')
                ->with(
                    'error',
                    'Data cabang tidak dapat dibuka.'
                );
        }
    }


    /**
     * =====================================================
     * UPDATE
     * =====================================================
     */
    public function update(
        CabangUpdateRequest $request,
        $id
    ) {
        try {

            $cabang = Cabang::findOrFail($id);

            $data = $request->validated();

            $cabang->update($data);


            return redirect()
                ->route('cabang.index')
                ->with(
                    'success',
                    'Data cabang berhasil diperbarui.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@update: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memperbarui data cabang.'
                );
        }
    }


    /**
     * =====================================================
     * DESTROY
     * =====================================================
     */
    public function destroy($id)
    {
        try {

            $cabang = Cabang::findOrFail($id);

            $cabang->delete();


            return redirect()
                ->route('cabang.index')
                ->with(
                    'success',
                    'Data cabang berhasil dihapus.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@destroy: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Data cabang tidak dapat dihapus.'
                );
        }
    }


    /**
     * =====================================================
     * LAPORAN PDF
     * =====================================================
     */
    public function laporanPdf(Request $request)
    {
        try {

            $query = Cabang::query();


            /*
             * Filter pencarian
             */
            if ($request->filled('katakunci')) {

                $katakunci = $request->katakunci;

                $query->where(function ($q) use ($katakunci) {

                    $q->where(
                        'nama_cabang',
                        'like',
                        "%{$katakunci}%"
                    )

                    ->orWhere(
                        'kode_cabang',
                        'like',
                        "%{$katakunci}%"
                    )

                    ->orWhere(
                        'alamat_cabang',
                        'like',
                        "%{$katakunci}%"
                    )

                    ->orWhere(
                        'no_telepon',
                        'like',
                        "%{$katakunci}%"
                    )

                    ->orWhere(
                        'email',
                        'like',
                        "%{$katakunci}%"
                    );

                });
            }


            /*
             * Ambil data
             */
            $cabang = $query
                ->orderBy('kode_cabang')
                ->get();


            $total = $cabang->count();


            /*
             * Nomor laporan
             */
            $no_laporan =
                'CBG-' .
                now()->format('YmdHis');


            /*
             * Tanggal laporan
             */
            $tanggalLaporan =
                now()->translatedFormat(
                    'd F Y H:i'
                );


            /*
             * Periode
             */
            $periode = 'Semua Data';


            /*
             * Generate PDF
             */
            $pdf = Pdf::loadView(
                'pages.cabang.laporan_pdf',
                compact(
                    'cabang',
                    'total',
                    'no_laporan',
                    'tanggalLaporan',
                    'periode'
                )
            )->setPaper(
                'a4',
                'landscape'
            );


            return $pdf->stream(
                'laporan-cabang.pdf'
            );

        } catch (\Exception $e) {

            Log::error(
                'Error CabangController@laporanPdf: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Laporan cabang tidak dapat dibuat.'
                );
        }
    }
}