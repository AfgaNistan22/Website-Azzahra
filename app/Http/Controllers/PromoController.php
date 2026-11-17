<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Perawatan;
use App\Http\Requests\PromoStoreRequest;
use App\Http\Requests\PromoUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PromoController extends Controller
{
    /**
     * Nonaktifkan otomatis promo yang sudah melewati tanggal selesai.
     * (Lapis kedua selain scheduler: promo:nonaktifkan-kedaluwarsa)
     */
    private function nonaktifkanKedaluwarsa(): void
    {
        try {
            Promo::kedaluwarsa()->update(['status' => 'Tidak Aktif']);
        } catch (\Exception $e) {
            Log::error(
                'Gagal menonaktifkan promo kedaluwarsa: ' .
                $e->getMessage()
            );
        }
    }

    /**
     * Jika tanggal selesai sudah lewat, status dipaksa Tidak Aktif.
     * perawatan_id kosong = promo berlaku untuk semua perawatan.
     */
    private function normalisasiData(array $data): array
    {
        // Jika tanggal selesai sudah lewat, status dipaksa Tidak Aktif
        if (
            !empty($data['tanggal_selesai']) &&
            Carbon::parse($data['tanggal_selesai'])->lt(today())
        ) {
            $data['status'] = 'Tidak Aktif';
        }

        // perawatan_id kosong = promo berlaku untuk semua perawatan
        if (empty($data['perawatan_id'])) {
            $data['perawatan_id'] = null;
        }

        // Khusus member? (false = semua pelanggan)
        $data['khusus_member'] = (bool) ($data['khusus_member'] ?? false);

        return $data;
    }

    /**
     * DAFTAR PROMO
     */
    public function index(Request $request)
    {
        try {

            $this->nonaktifkanKedaluwarsa();

            $katakunci = $request->katakunci;

            $perPage = $request->input('per_page', 5);

            $data = Promo::with('perawatan')
                ->when($katakunci, function ($query, $katakunci) {

                    $query->where(function ($q) use ($katakunci) {

                        $q->where('nama', 'like', "%{$katakunci}%")
                            ->orWhere('deskripsi', 'like', "%{$katakunci}%")
                            ->orWhere('jenis', 'like', "%{$katakunci}%")
                            ->orWhere('status', 'like', "%{$katakunci}%")
                            ->orWhereHas('perawatan', function ($perawatan) use ($katakunci) {
                                $perawatan->where(
                                    'nama',
                                    'like',
                                    "%{$katakunci}%"
                                );
                            });

                        // Pencarian "semua" / "semua perawatan"
                        if (stripos('semua perawatan', $katakunci) !== false) {
                            $q->orWhereNull('perawatan_id');
                        }

                        // Pencarian "member" / "khusus member"  <-- TAMBAHKAN DI SINI
                        if (stripos('khusus member', $katakunci) !== false) {
                            $q->orWhere('khusus_member', true);
                        }   

                    });

                })
                ->orderByDesc('tanggal_mulai')
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();

            $totalPromo = Promo::count();

            $promoAktif = Promo::where(
                'status',
                'Aktif'
            )->count();

            $promoTidakAktif = Promo::where(
                'status',
                'Tidak Aktif'
            )->count();

            return view(
                'pages.promo.index',
                compact(
                    'data',
                    'totalPromo',
                    'promoAktif',
                    'promoTidakAktif'
                )
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal memuat data promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memuat data promo.'
                );
        }
    }


    /**
     * FORM TAMBAH PROMO
     */
    public function create()
    {
        try {

            // Data perawatan untuk dropdown
            // (opsi "Semua Perawatan" ada di view, value kosong)
            $perawatan = Perawatan::orderBy('nama')->get();

            return view(
                'pages.promo.create',
                compact('perawatan')
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal membuka form tambah promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('promo.index')
                ->with(
                    'error',
                    'Form tambah promo gagal dibuka.'
                );
        }
    }


    /**
     * SIMPAN PROMO
     */
    public function store(PromoStoreRequest $request)
    {
        try {

            $data = $this->normalisasiData(
                $request->validated()
            );

            /*
            |--------------------------------------------------------------------------
            | UPLOAD FOTO
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('foto')) {

                $data['foto'] = $request
                    ->file('foto')
                    ->store(
                        'foto_promo',
                        'public'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | SIMPAN DATA PROMO
            |--------------------------------------------------------------------------
            |
            | perawatan_id = null  -> promo berlaku untuk semua perawatan
            | perawatan_id = id    -> promo hanya untuk perawatan tersebut
            |
            */

            Promo::create($data);

            return redirect()
                ->route('promo.index')
                ->with(
                    'success',
                    'Data promo berhasil ditambahkan.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menyimpan promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyimpan data promo.'
                );
        }
    }


    /**
     * DETAIL PROMO
     */
    public function show($id)
    {
        try {

            $data = Promo::with('perawatan')
                ->findOrFail($id);

            return view(
                'pages.promo.detail',
                compact('data')
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menampilkan detail promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('promo.index')
                ->with(
                    'error',
                    'Data promo tidak ditemukan.'
                );
        }
    }


    /**
     * FORM EDIT PROMO
     */
    public function edit($id)
    {
        try {

            $data = Promo::with('perawatan')
                ->findOrFail($id);

            // Data perawatan untuk dropdown edit
            $perawatan = Perawatan::orderBy('nama')->get();

            return view(
                'pages.promo.edit',
                compact(
                    'data',
                    'perawatan'
                )
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal membuka edit promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('promo.index')
                ->with(
                    'error',
                    'Data promo tidak dapat dibuka.'
                );
        }
    }


    /**
     * UPDATE PROMO
     */
    public function update(
        PromoUpdateRequest $request,
        $id
    ) {
        try {

            $promo = Promo::findOrFail($id);

            $data = $this->normalisasiData(
                $request->validated()
            );


            /*
            |--------------------------------------------------------------------------
            | UPDATE FOTO
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('foto')) {

                if ($promo->foto) {

                    Storage::disk('public')
                        ->delete($promo->foto);
                }

                $data['foto'] = $request
                    ->file('foto')
                    ->store(
                        'foto_promo',
                        'public'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE DATA PROMO
            |--------------------------------------------------------------------------
            */

            $promo->update($data);

            return redirect()
                ->route('promo.index')
                ->with(
                    'success',
                    'Data promo berhasil diperbarui.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal memperbarui promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memperbarui data promo.'
                );
        }
    }


    /**
     * HAPUS PROMO
     */
    public function destroy($id)
    {
        try {

            $promo = Promo::findOrFail($id);

            if ($promo->foto) {

                Storage::disk('public')
                    ->delete($promo->foto);
            }

            $promo->delete();

            return redirect()
                ->route('promo.index')
                ->with(
                    'success',
                    'Data promo berhasil dihapus.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Gagal menghapus promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menghapus data promo.'
                );
        }
    }


    /**
     * LAPORAN PDF
     */
    public function laporanPdf(Request $request)
    {
        try {

            $this->nonaktifkanKedaluwarsa();

            $query = Promo::with('perawatan');


            /*
            |--------------------------------------------------------------------------
            | FILTER TANGGAL MULAI
            |--------------------------------------------------------------------------
            */

            if ($request->filled('tanggal_mulai')) {

                $query->whereDate(
                    'tanggal_mulai',
                    '>=',
                    $request->tanggal_mulai
                );
            }


            /*
            |--------------------------------------------------------------------------
            | FILTER TANGGAL SELESAI
            |--------------------------------------------------------------------------
            */

            if ($request->filled('tanggal_selesai')) {

                $query->whereDate(
                    'tanggal_selesai',
                    '<=',
                    $request->tanggal_selesai
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if ($request->filled('katakunci')) {

                $katakunci = $request->katakunci;

                $query->where(function ($q) use ($katakunci) {

                    $q->where(
                        'nama',
                        'like',
                        "%{$katakunci}%"
                    )
                    ->orWhere(
                        'jenis',
                        'like',
                        "%{$katakunci}%"
                    )
                    ->orWhere(
                        'status',
                        'like',
                        "%{$katakunci}%"
                    )
                    ->orWhereHas(
                        'perawatan',
                        function ($perawatan) use ($katakunci) {

                            $perawatan->where(
                                'nama',
                                'like',
                                "%{$katakunci}%"
                            );
                        }
                    );

                    // Pencarian "semua" / "semua perawatan"
                    if (stripos('semua perawatan', $katakunci) !== false) {
                        $q->orWhereNull('perawatan_id');
                    }

                    // Pencarian "member" / "khusus member"  <-- TAMBAHKAN DI SINI
                    if (stripos('khusus member', $katakunci) !== false) {
                        $q->orWhere('khusus_member', true);
                    }

                });
            }


            /*
            |--------------------------------------------------------------------------
            | DATA PROMO
            |--------------------------------------------------------------------------
            */

            $promo = $query
                ->orderBy('tanggal_mulai')
                ->orderBy('nama')
                ->get();


            $total = $promo->count();

            $aktif = $promo
                ->where(
                    'status',
                    'Aktif'
                )
                ->count();

            $tidakAktif = $promo
                ->where(
                    'status',
                    'Tidak Aktif'
                )
                ->count();


            /*
            |--------------------------------------------------------------------------
            | NOMOR LAPORAN
            |--------------------------------------------------------------------------
            */

            $no_laporan =
                'PROMO-' .
                now()->format('YmdHis');


            /*
            |--------------------------------------------------------------------------
            | TANGGAL LAPORAN
            |--------------------------------------------------------------------------
            */

            $tanggalLaporan =
                now()->translatedFormat(
                    'd F Y H:i'
                );


            /*
            |--------------------------------------------------------------------------
            | PERIODE
            |--------------------------------------------------------------------------
            */

            $periode = 'Semua Data';

            if (
                $request->filled('tanggal_mulai') &&
                $request->filled('tanggal_selesai')
            ) {

                $periode =
                    Carbon::parse(
                        $request->tanggal_mulai
                    )->format('d/m/Y')
                    . ' - ' .
                    Carbon::parse(
                        $request->tanggal_selesai
                    )->format('d/m/Y');
            }


            /*
            |--------------------------------------------------------------------------
            | PDF
            |--------------------------------------------------------------------------
            */

            $pdf = Pdf::loadView(
                'pages.promo.laporan_pdf',
                compact(
                    'promo',
                    'total',
                    'aktif',
                    'tidakAktif',
                    'no_laporan',
                    'tanggalLaporan',
                    'periode'
                )
            )->setPaper(
                'a4',
                'landscape'
            );


            return $pdf->stream(
                'laporan-promo.pdf'
            );

        } catch (\Exception $e) {

            Log::error(
                'Gagal membuat laporan promo: ' .
                $e->getMessage()
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Laporan promo tidak dapat dibuat.'
                );
        }
    }
}