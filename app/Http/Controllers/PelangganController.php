<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Http\Requests\PelangganStoreRequest;
use App\Http\Requests\PelangganUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PelangganController extends Controller
{
    /**
     * Menampilkan data pelanggan
     */
    public function index(Request $request)
    {
        try {

            $katakunci = $request->katakunci;

            $perPage = $request->input('per_page', 5);

            $data = Pelanggan::query()
                ->when($katakunci, function ($query, $katakunci) {

                    $query->where(function ($q) use ($katakunci) {

                        $q->where('nama', 'like', "%{$katakunci}%")
                            ->orWhere('no_telepon', 'like', "%{$katakunci}%")
                            ->orWhere('email', 'like', "%{$katakunci}%")
                            ->orWhere('alamat', 'like', "%{$katakunci}%");

                        // Cari "Member"
                        if (strtolower($katakunci) === 'member') {

                            $q->orWhere('member', 'Ya');

                        }

                        // Cari "Bukan Member"
                        if (
                            strtolower($katakunci) === 'bukan member' ||
                            strtolower($katakunci) === 'bukan'
                        ) {

                            $q->orWhere('member', 'Tidak');

                        }

                    });

                })
                ->latest()
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return view(
                'pages.pelanggan.index',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error('Gagal menampilkan data pelanggan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengambil data pelanggan.'
                );
        }
    }


    /**
     * Menampilkan form tambah pelanggan
     */
    public function create()
    {
        return view('pages.pelanggan.create');
    }


    /**
     * Menyimpan pelanggan baru
     */
    public function store(PelangganStoreRequest $request)
    {
        try {

            $validated = $request->validated();

            // Karena salon khusus perempuan
            $validated['jk'] = 'Perempuan';


            /*
            |--------------------------------------------------------------------------
            | UPLOAD FOTO
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('foto')) {

                $file = $request->file('foto');

                // Membuat nama file unik
                $namaFoto = time() . '_' . uniqid() . '.' .
                            $file->getClientOriginalExtension();

                // Simpan foto
                $file->storeAs(
                    'foto_pelanggan',
                    $namaFoto,
                    'public'
                );

                // Simpan nama file ke database
                $validated['foto'] = $namaFoto;
            }


            /*
            |--------------------------------------------------------------------------
            | SIMPAN DATA
            |--------------------------------------------------------------------------
            */

            Pelanggan::create($validated);


            return redirect()
                ->route('pelanggan.index')
                ->with(
                    'success',
                    'Data pelanggan berhasil ditambahkan.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menambahkan pelanggan: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menambahkan data pelanggan.'
                );
        }
    }


    /**
     * Menampilkan detail pelanggan
     */
    public function show(string $id)
    {
        try {

            $data = Pelanggan::findOrFail($id);

            return view(
                'pages.pelanggan.detail',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan detail pelanggan: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('pelanggan.index')
                ->with(
                    'error',
                    'Data pelanggan tidak ditemukan.'
                );
        }
    }


    /**
     * Menampilkan form edit
     */
    public function edit(string $id)
    {
        try {

            $data = Pelanggan::findOrFail($id);

            return view(
                'pages.pelanggan.edit',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan form edit pelanggan: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('pelanggan.index')
                ->with(
                    'error',
                    'Data pelanggan tidak ditemukan.'
                );
        }
    }


    /**
     * Mengupdate data pelanggan
     */
    public function update(
        PelangganUpdateRequest $request,
        string $id
    ) {
        try {

            $data = Pelanggan::findOrFail($id);

            $validated = $request->validated();

            // Salon khusus perempuan
            $validated['jk'] = 'Perempuan';


            /*
            |--------------------------------------------------------------------------
            | UPLOAD FOTO BARU
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('foto')) {

                // Hapus foto lama jika ada
                if (
                    $data->foto &&
                    Storage::disk('public')->exists(
                        'foto_pelanggan/' . $data->foto
                    )
                ) {

                    Storage::disk('public')->delete(
                        'foto_pelanggan/' . $data->foto
                    );
                }


                // Upload foto baru
                $file = $request->file('foto');

                $namaFoto = time() . '_' . uniqid() . '.' .
                            $file->getClientOriginalExtension();

                $file->storeAs(
                    'foto_pelanggan',
                    $namaFoto,
                    'public'
                );


                // Simpan nama foto baru
                $validated['foto'] = $namaFoto;

            } else {

                // Pertahankan foto lama
                $validated['foto'] = $data->foto;
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE DATA
            |--------------------------------------------------------------------------
            */

            $data->update($validated);


            return redirect()
                ->route('pelanggan.index')
                ->with(
                    'success',
                    'Data pelanggan berhasil diperbarui.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal memperbarui pelanggan: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memperbarui data pelanggan.'
                );
        }
    }


    /**
     * Menghapus pelanggan
     */
    public function destroy(string $id)
    {
        try {

            $data = Pelanggan::findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | HAPUS FOTO
            |--------------------------------------------------------------------------
            */

            if (
                $data->foto &&
                Storage::disk('public')->exists(
                    'foto_pelanggan/' . $data->foto
                )
            ) {

                Storage::disk('public')->delete(
                    'foto_pelanggan/' . $data->foto
                );
            }


            /*
            |--------------------------------------------------------------------------
            | HAPUS DATA
            |--------------------------------------------------------------------------
            */

            $data->delete();


            return redirect()
                ->route('pelanggan.index')
                ->with(
                    'success',
                    'Data pelanggan berhasil dihapus.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menghapus pelanggan: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('pelanggan.index')
                ->with(
                    'error',
                    'Terjadi kesalahan saat menghapus data pelanggan.'
                );
        }
    }


    /**
     * Laporan PDF
     */
    public function laporanPdf(Request $request)
    {
        try {

            $pelanggan = Pelanggan::orderBy('nama', 'asc')->get();

            $total = $pelanggan->count();


            /*
            |--------------------------------------------------------------------------
            | JENIS KELAMIN
            |--------------------------------------------------------------------------
            */

            $jk = [
                'Perempuan' => $pelanggan
                    ->where('jk', 'Perempuan')
                    ->count(),
            ];


            /*
            |--------------------------------------------------------------------------
            | KELOMPOK UMUR
            |--------------------------------------------------------------------------
            */

            $umurGroup = [
                'Di bawah 20 tahun' => 0,
                '20 - 29 tahun' => 0,
                '30 - 39 tahun' => 0,
                '40 - 49 tahun' => 0,
                '50 tahun ke atas' => 0,
            ];


            foreach ($pelanggan as $item) {

                if (!$item->tgl_lahir) {
                    continue;
                }

                $umur = $item->tgl_lahir->age;

                if ($umur < 20) {

                    $umurGroup['Di bawah 20 tahun']++;

                } elseif ($umur <= 29) {

                    $umurGroup['20 - 29 tahun']++;

                } elseif ($umur <= 39) {

                    $umurGroup['30 - 39 tahun']++;

                } elseif ($umur <= 49) {

                    $umurGroup['40 - 49 tahun']++;

                } else {

                    $umurGroup['50 tahun ke atas']++;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | DATA LAPORAN
            |--------------------------------------------------------------------------
            */

            $no_laporan = 'LAP-' . date('YmdHis');

            $tanggal = now()->translatedFormat('d F Y');

            $alamat_salon = 'Azzahra Salon dan Spa Muslimah';

            $telepon_salon = '-';


            /*
            |--------------------------------------------------------------------------
            | PDF
            |--------------------------------------------------------------------------
            */

            $pdf = Pdf::loadView(
                'pages.pelanggan.laporan_pdf',
                compact(
                    'no_laporan',
                    'tanggal',
                    'alamat_salon',
                    'telepon_salon',
                    'total',
                    'jk',
                    'umurGroup',
                    'pelanggan'
                )
            );

            $pdf->setPaper('A4', 'portrait');


            return $pdf->download(
                'laporan_pelanggan.pdf'
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal membuat laporan PDF pelanggan: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat membuat laporan PDF.'
                );
        }
    }
}