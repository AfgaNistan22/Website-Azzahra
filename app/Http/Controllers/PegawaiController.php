<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Http\Requests\PegawaiStoreRequest;
use App\Http\Requests\PegawaiUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PegawaiController extends Controller
{
    /**
     * Menampilkan data pegawai
     */
    public function index(Request $request)
    {
        try {

            $katakunci = $request->katakunci;

            $perPage = $request->input('per_page', 5);

            $data = Pegawai::query()
                ->when($katakunci, function ($query, $katakunci) {

                    $query->where(function ($q) use ($katakunci) {

                        $q->where('nama', 'like', '%' . $katakunci . '%')
                            ->orWhere('no_telepon', 'like', '%' . $katakunci . '%')
                            ->orWhere('email', 'like', '%' . $katakunci . '%')
                            ->orWhere('alamat', 'like', '%' . $katakunci . '%');

                    });

                })
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return view(
                'pages.pegawai.index',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan data pegawai: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengambil data pegawai.'
                );
        }
    }


    /**
     * Menampilkan form tambah pegawai
     */
    public function create()
    {
        return view('pages.pegawai.create');
    }


    /**
     * Menyimpan pegawai baru
     */
    public function store(PegawaiStoreRequest $request)
    {
        try {

            $validated = $request->validated();


            // ==========================
            // UPLOAD FOTO
            // ==========================

            if ($request->hasFile('foto')) {

                $file = $request->file('foto');

                $namaFoto = time() . '_' .
                    uniqid() . '.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'foto_pegawai',
                    $namaFoto,
                    'public'
                );

                $validated['foto'] = $namaFoto;
            }


            // ==========================
            // SIMPAN DATA
            // ==========================

            Pegawai::create($validated);


            return redirect()
                ->route('pegawai.index')
                ->with(
                    'success',
                    'Data pegawai berhasil ditambahkan.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menambahkan pegawai: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menambahkan data pegawai.'
                );
        }
    }


    /**
     * Menampilkan detail pegawai
     */
    public function show(string $id)
    {
        try {

            $data = Pegawai::findOrFail($id);

            return view(
                'pages.pegawai.detail',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan detail pegawai: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('pegawai.index')
                ->with(
                    'error',
                    'Data pegawai tidak ditemukan.'
                );
        }
    }


    /**
     * Menampilkan form edit pegawai
     */
    public function edit(string $id)
    {
        try {

            $data = Pegawai::findOrFail($id);

            return view(
                'pages.pegawai.edit',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan form edit pegawai: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('pegawai.index')
                ->with(
                    'error',
                    'Data pegawai tidak ditemukan.'
                );
        }
    }


    /**
     * Mengupdate data pegawai
     */
    public function update(
        PegawaiUpdateRequest $request,
        string $id
    ) {
        try {

            // ==========================
            // CARI DATA PEGAWAI
            // ==========================

            $data = Pegawai::findOrFail($id);


            // ==========================
            // VALIDASI
            // ==========================

            $validated = $request->validated();


            // ==========================
            // UPLOAD FOTO BARU
            // ==========================

            if ($request->hasFile('foto')) {

                $file = $request->file('foto');


                // Hapus foto lama
                if (
                    $data->foto &&
                    Storage::disk('public')->exists(
                        'foto_pegawai/' . $data->foto
                    )
                ) {

                    Storage::disk('public')->delete(
                        'foto_pegawai/' . $data->foto
                    );
                }


                // Nama foto baru
                $namaFoto = time() . '_' .
                    uniqid() . '.' .
                    $file->getClientOriginalExtension();


                // Simpan foto baru
                $file->storeAs(
                    'foto_pegawai',
                    $namaFoto,
                    'public'
                );


                $validated['foto'] = $namaFoto;

            } else {

                // Tidak mengganti foto
                $validated['foto'] = $data->foto;

            }


            // ==========================
            // UPDATE DATABASE
            // ==========================

            $data->update($validated);


            // ==========================
            // REDIRECT
            // ==========================

            return redirect()
                ->route('pegawai.index')
                ->with(
                    'success',
                    'Data pegawai berhasil diperbarui.'
                );


        } catch (\Exception $e) {

            Log::error(
                'Gagal memperbarui pegawai: ' .
                $e->getMessage()
            );


            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memperbarui data pegawai.'
                );
        }
    }


    /**
     * Menghapus pegawai
     */
    public function destroy(string $id)
    {
        try {

            $data = Pegawai::findOrFail($id);


            // ==========================
            // HAPUS FOTO
            // ==========================

            if (
                $data->foto &&
                Storage::disk('public')->exists(
                    'foto_pegawai/' . $data->foto
                )
            ) {

                Storage::disk('public')->delete(
                    'foto_pegawai/' . $data->foto
                );
            }


            // ==========================
            // HAPUS DATA
            // ==========================

            $data->delete();


            return redirect()
                ->route('pegawai.index')
                ->with(
                    'success',
                    'Data pegawai berhasil dihapus.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menghapus pegawai: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('pegawai.index')
                ->with(
                    'error',
                    'Terjadi kesalahan saat menghapus data pegawai.'
                );
        }
    }


    /**
     * Laporan PDF Pegawai
     */
    public function laporanPdf()
    {
        try {

            $pegawai = Pegawai::orderBy('nama', 'asc')->get();

            $total = $pegawai->count();

            $aktif = $pegawai
                ->where('status', 'Aktif')
                ->count();

            $tidakAktif = $pegawai
                ->where('status', 'Tidak Aktif')
                ->count();


            // ==========================
            // DATA LAPORAN
            // ==========================

            $no_laporan = 'LAP-PEGAWAI-' . date('YmdHis');

            $tanggal = now()->translatedFormat('d F Y');

            $alamat_salon = 'Azzahra Salon dan Spa Muslimah';

            $telepon_salon = '-';


            // ==========================
            // PDF
            // ==========================

            $pdf = Pdf::loadView(
                'pages.pegawai.laporan_pdf',
                compact(
                    'pegawai',
                    'total',
                    'aktif',
                    'tidakAktif',
                    'no_laporan',
                    'tanggal',
                    'alamat_salon',
                    'telepon_salon'
                )
            );


            $pdf->setPaper('A4', 'portrait');


            return $pdf->download(
                'laporan_pegawai.pdf'
            );


        } catch (\Exception $e) {

            Log::error(
                'Gagal membuat laporan PDF pegawai: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat membuat laporan PDF pegawai.'
                );
        }
    }
}