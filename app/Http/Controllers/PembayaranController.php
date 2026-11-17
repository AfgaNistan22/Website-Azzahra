<?php

namespace App\Http\Controllers;

use App\Http\Requests\PembayaranStoreRequest;
use App\Http\Requests\PembayaranUpdateRequest;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Pemasukan;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran
     */
    public function index(Request $request)
    {
        try {

            $query = Pembayaran::with([
                'reservasi.pelanggan',
                'reservasi.perawatan',
                'reservasi.perawatans',
                'reservasi.pegawai',
                'promo.perawatan'
            ]);

            // =========================
            // SEARCH
            // =========================
            if ($request->filled('katakunci')) {

                $katakunci = $request->katakunci;

                $query->where(function ($q) use ($katakunci) {

                    $q->where('metode_pembayaran', 'like', '%' . $katakunci . '%')

                        ->orWhere('status', 'like', '%' . $katakunci . '%')

                        ->orWhereHas('promo', function ($promo) use ($katakunci) {
                            $promo->where('nama', 'like', '%' . $katakunci . '%');
                        })

                        ->orWhereHas('reservasi', function ($reservasi) use ($katakunci) {
                            $reservasi->where('kode_reservasi', 'like', '%' . $katakunci . '%');
                        })

                        ->orWhereHas('reservasi.pelanggan', function ($pelanggan) use ($katakunci) {
                            $pelanggan->where('nama', 'like', '%' . $katakunci . '%');
                        })

                        ->orWhereHas('reservasi.perawatan', function ($perawatan) use ($katakunci) {
                            $perawatan->where('nama', 'like', '%' . $katakunci . '%');
                        })

                        ->orWhereHas('reservasi.perawatans', function ($perawatan) use ($katakunci) {
                            $perawatan->where('nama', 'like', '%' . $katakunci . '%');
                        })

                        ->orWhereHas('reservasi.pegawai', function ($pegawai) use ($katakunci) {
                            $pegawai->where('nama', 'like', '%' . $katakunci . '%');
                        });
                });
            }


            // =========================
            // FILTER STATUS
            // =========================
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }


            // =========================
            // FILTER TANGGAL
            // =========================
            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal_bayar', $request->tanggal);
            }


            // =========================
            // PAGINATION
            // =========================
            $data = $query
                ->orderByRaw('CASE WHEN tanggal_bayar IS NULL THEN 1 ELSE 0 END')
                ->latest('tanggal_bayar')
                ->latest('id')
                ->paginate(10)
                ->withQueryString();


            // =========================
            // SUMMARY
            // =========================
            $totalPembayaran = Pembayaran::count();
            $totalLunas      = Pembayaran::where('status', 'Lunas')->count();
            $totalMenunggu   = Pembayaran::where('status', 'Menunggu')->count();
            $totalDibatalkan = Pembayaran::where('status', 'Dibatalkan')->count();
            $totalNominal    = Pembayaran::where('status', 'Lunas')->sum('jumlah');


            return view(
                'pages.pembayaran.index',
                compact(
                    'data',
                    'totalPembayaran',
                    'totalLunas',
                    'totalMenunggu',
                    'totalDibatalkan',
                    'totalNominal'
                )
            );

        } catch (\Throwable $e) {

            Log::error(
                'Gagal menampilkan pembayaran: ' . $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );

            return redirect()
                ->back()
                ->with('error', 'Data pembayaran gagal ditampilkan.');
        }
    }


    /**
     * Form tambah pembayaran
     */
    public function create(Request $request)
    {
        try {

            $kodeReservasi = $request->query('kode_reservasi');

            $reservasi = Reservasi::with([
                'pelanggan',
                'perawatan',
                'perawatans',
                'pegawai',
                'pembayaran'
            ])
                ->where('status', '!=', 'Dibatalkan')
                ->whereDoesntHave('pembayaran')
                ->orderBy('tanggal')
                ->orderBy('jam_mulai')
                ->get();

            return view(
                'pages.pembayaran.create',
                compact('reservasi', 'kodeReservasi')
            );

        } catch (\Throwable $e) {

            Log::error('Gagal membuka form pembayaran: ' . $e->getMessage());

            return redirect()
                ->route('pembayaran.index')
                ->with('error', 'Form pembayaran gagal dibuka.');
        }
    }


    /**
     * Simpan pembayaran manual
     */
    public function store(PembayaranStoreRequest $request)
    {
        DB::beginTransaction();

        try {

            $reservasi = Reservasi::with([
                'pelanggan',
                'perawatan',
                'perawatans',
                'pegawai',
                'pembayaran'
            ])->findOrFail($request->reservasi_id);


            // =========================
            // CEK STATUS
            // =========================
            if ($reservasi->status === 'Dibatalkan') {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Reservasi yang dibatalkan tidak dapat dibayar.');
            }


            // =========================
            // CEK KODE
            // =========================
            if (empty($reservasi->kode_reservasi)) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Reservasi belum memiliki kode reservasi.');
            }


            // =========================
            // CEK DUPLIKAT
            // =========================
            if ($reservasi->pembayaran) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Reservasi tersebut sudah memiliki pembayaran.');
            }


            // =========================
            // HITUNG TOTAL
            // =========================
            $jumlah = 0;

            if (
                $reservasi->perawatans &&
                $reservasi->perawatans->count() > 0
            ) {

                $jumlah = $reservasi->perawatans->sum(function ($perawatan) {

                    $harga = $perawatan->pivot->harga ?? $perawatan->harga;
                    $qty   = $perawatan->pivot->jumlah ?? 1;

                    return ((int) $harga) * ((int) $qty);
                });
            }


            // =========================
            // FALLBACK
            // =========================
            if ($jumlah <= 0 && $reservasi->perawatan) {
                $jumlah = (int) $reservasi->perawatan->harga;
            }


            // =========================
            // CEK TOTAL
            // =========================
            if ($jumlah <= 0) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Total harga perawatan tidak ditemukan.');
            }


            // =========================
            // SIMPAN
            // =========================
            $pembayaran = Pembayaran::create([
                'reservasi_id'          => $reservasi->id,
                'tanggal_bayar'         => $request->tanggal_bayar,
                'metode_pembayaran'     => $request->metode_pembayaran,
                'jumlah_sebelum_diskon' => $jumlah,
                'jumlah_diskon'         => 0,
                'jumlah'                => $jumlah,
                'promo_id'              => null,
                'status'                => 'Lunas',
                'catatan'               => $request->catatan,
            ]);


            // =========================
            // SINKRONISASI PEMASUKAN
            // =========================
            $pembayaran->refresh();

            if ($pembayaran->status === 'Lunas') {
                Pemasukan::syncFromPembayaran($pembayaran);
            } else {
                Pemasukan::cancelFromPembayaran($pembayaran);
            }


            // =========================
            // KONFIRMASI
            // =========================
            $reservasi->update([
                'status' => 'Dikonfirmasi'
            ]);


            DB::commit();


            return redirect()
                ->route('pembayaran.show', $pembayaran->id)
                ->with('success', 'Pembayaran berhasil disimpan dan reservasi berhasil dikonfirmasi.');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal menyimpan pembayaran: ' . $e->getMessage(),
                [
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pembayaran gagal disimpan: ' . $e->getMessage());
        }
    }


    /**
     * Detail pembayaran
     */
    public function show($id)
    {
        try {

            if (!is_numeric($id)) {

                return redirect()
                    ->route('pembayaran.index')
                    ->with('error', 'ID pembayaran tidak valid.');
            }


            $data = Pembayaran::with([
                'reservasi.pelanggan',
                'reservasi.perawatan',
                'reservasi.perawatans',
                'reservasi.pegawai',
                'promo.perawatan'
            ])->find($id);


            if (!$data) {

                return redirect()
                    ->route('pembayaran.index')
                    ->with('error', 'Data pembayaran tidak ditemukan.');
            }


            if (!$data->reservasi) {

                return redirect()
                    ->route('pembayaran.index')
                    ->with('error', 'Reservasi pembayaran tidak ditemukan.');
            }


            return view('pages.pembayaran.detail', compact('data'));

        } catch (\Throwable $e) {

            Log::error(
                'Gagal menampilkan detail pembayaran ID ' . $id . ': ' . $e->getMessage()
            );

            return redirect()
                ->route('pembayaran.index')
                ->with('error', 'Gagal membuka detail pembayaran: ' . $e->getMessage());
        }
    }


    /**
     * Form edit pembayaran
     */
    public function edit($id)
    {
        try {

            $pembayaran = Pembayaran::with([
                'reservasi.pelanggan',
                'reservasi.perawatan',
                'reservasi.perawatans',
                'reservasi.pegawai',
                'promo.perawatan'
            ])->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | PEMBAYARAN LUNAS TIDAK BOLEH DIEDIT
            |--------------------------------------------------------------------------
            */
            if ($pembayaran->status === 'Lunas') {

                return redirect()
                    ->route('pembayaran.show', $pembayaran->id)
                    ->with('error', 'Pembayaran yang sudah Lunas tidak dapat diubah.');
            }


            /*
            |--------------------------------------------------------------------------
            | PROMO AKTIF
            |--------------------------------------------------------------------------
            */
            $promos = Promo::with('perawatan')
                ->where('status', 'Aktif')
                ->whereDate('tanggal_mulai', '<=', now()->toDateString())
                ->whereDate('tanggal_selesai', '>=', now()->toDateString())
                ->orderBy('nama')
                ->get();


            return view(
                'pages.pembayaran.edit',
                compact('pembayaran', 'promos')
            );

        } catch (\Throwable $e) {

            Log::error(
                'Gagal membuka edit pembayaran ID ' . $id . ': ' . $e->getMessage()
            );

            return redirect()
                ->route('pembayaran.index')
                ->with('error', 'Data pembayaran tidak ditemukan: ' . $e->getMessage());
        }
    }


    /**
     * Update pembayaran
     *
     * Diskon promo dihitung dari harga PERAWATAN YANG TERKAIT PROMO saja
     * (bukan dari total seluruh perawatan). Jika promo tidak terikat
     * perawatan tertentu (perawatan_id kosong), dasar diskon = total semua.
     */
    public function update(PembayaranUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        try {

            $pembayaran = Pembayaran::with([
                'reservasi.pelanggan',
                'reservasi.perawatan',
                'reservasi.perawatans',
                'reservasi.pegawai',
                'promo.perawatan'
            ])->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | PEMBAYARAN LUNAS TIDAK BOLEH DIUBAH
            |--------------------------------------------------------------------------
            */
            if ($pembayaran->status === 'Lunas') {

                DB::rollBack();

                return redirect()
                    ->route('pembayaran.show', $pembayaran->id)
                    ->with('error', 'Pembayaran yang sudah Lunas tidak dapat diubah.');
            }


            $validated = $request->validated();
            $reservasi = $pembayaran->reservasi;


            /*
            |--------------------------------------------------------------------------
            | CEK RESERVASI
            |--------------------------------------------------------------------------
            */
            if ($reservasi && $reservasi->status === 'Dibatalkan') {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Pembayaran dari reservasi yang dibatalkan tidak dapat diperbarui.');
            }


            /*
            |--------------------------------------------------------------------------
            | HITUNG TOTAL HARGA SEMUA PERAWATAN
            |--------------------------------------------------------------------------
            */
            $jumlah = 0;

            if (
                $reservasi &&
                $reservasi->perawatans &&
                $reservasi->perawatans->count() > 0
            ) {

                $jumlah = $reservasi->perawatans->sum(function ($perawatan) {

                    $harga = $perawatan->pivot->harga ?? $perawatan->harga;
                    $qty   = $perawatan->pivot->jumlah ?? 1;

                    return ((int) $harga) * ((int) $qty);
                });
            }

            // Fallback data lama (1 perawatan)
            if (
                $jumlah <= 0 &&
                $reservasi &&
                $reservasi->perawatan
            ) {
                $jumlah = (int) $reservasi->perawatan->harga;
            }

            if ($jumlah <= 0) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Total harga perawatan tidak ditemukan.');
            }


            /*
            |--------------------------------------------------------------------------
            | DEFAULT (TANPA PROMO)
            |--------------------------------------------------------------------------
            */
            $promo               = null;
            $jumlahSebelumDiskon = $jumlah;
            $jumlahDiskon        = 0;
            $jumlahAkhir         = $jumlah;


            /*
            |--------------------------------------------------------------------------
            | VALIDASI & HITUNG PROMO
            |--------------------------------------------------------------------------
            */
            if (!empty($validated['promo_id'])) {

                $promo = Promo::with('perawatan')->find($validated['promo_id']);

                // Promo ada?
                if (!$promo) {
                    throw new \Exception('Promo yang dipilih tidak ditemukan.');
                }

                // Status promo
                if ($promo->status !== 'Aktif') {
                    throw new \Exception('Promo tersebut sudah tidak aktif.');
                }

                // Tanggal promo
                $tanggalBayar   = Carbon::parse($validated['tanggal_bayar']);
                $tanggalMulai   = Carbon::parse($promo->tanggal_mulai);
                $tanggalSelesai = Carbon::parse($promo->tanggal_selesai);

                if (
                    $tanggalBayar->lt($tanggalMulai) ||
                    $tanggalBayar->gt($tanggalSelesai)
                ) {
                    throw new \Exception('Promo tidak berlaku pada tanggal pembayaran tersebut.');
                }


                /*
                |--------------------------------------------------------------------------
                | DASAR DISKON
                | - promo untuk perawatan tertentu -> hanya harga perawatan itu
                | - promo untuk semua perawatan    -> total seluruh perawatan
                |--------------------------------------------------------------------------
                */
                $dasarDiskon = $jumlah;

                if ($promo->perawatan_id) {

                    $dasarDiskon = 0;

                    // Multi perawatan
                    if (
                        $reservasi &&
                        $reservasi->perawatans &&
                        $reservasi->perawatans->count() > 0
                    ) {

                        $perawatanPromo = $reservasi
                            ->perawatans
                            ->firstWhere('id', $promo->perawatan_id);

                        if ($perawatanPromo) {

                            $harga = $perawatanPromo->pivot->harga ?? $perawatanPromo->harga;
                            $qty   = $perawatanPromo->pivot->jumlah ?? 1;

                            $dasarDiskon = ((int) $harga) * ((int) $qty);
                        }
                    }

                    // Fallback data lama (1 perawatan)
                    if (
                        $dasarDiskon <= 0 &&
                        $reservasi &&
                        $reservasi->perawatan &&
                        $reservasi->perawatan->id == $promo->perawatan_id
                    ) {
                        $dasarDiskon = (int) $reservasi->perawatan->harga;
                    }

                    // Promo tidak sesuai dengan perawatan di reservasi
                    if ($dasarDiskon <= 0) {

                        throw new \Exception(
                            'Promo "' . $promo->nama .
                            '" hanya berlaku untuk perawatan "' .
                            optional($promo->perawatan)->nama . '".'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | HITUNG DISKON
                |--------------------------------------------------------------------------
                */
                if ($promo->jenis === 'Persentase') {
                    $jumlahDiskon = ($dasarDiskon * $promo->nilai) / 100;
                } else {
                    $jumlahDiskon = $promo->nilai;
                }

                // Diskon maksimal = dasar diskon (harga perawatan yang kena promo)
                $jumlahDiskon = min($jumlahDiskon, $dasarDiskon);
                $jumlahDiskon = (int) round($jumlahDiskon);

                // Total akhir = total semua perawatan - diskon
                $jumlahAkhir = $jumlah - $jumlahDiskon;
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE PEMBAYARAN
            |--------------------------------------------------------------------------
            */
            $pembayaran->update([
                'promo_id'              => $promo?->id,
                'tanggal_bayar'         => $validated['tanggal_bayar'],
                'metode_pembayaran'     => $validated['metode_pembayaran'],
                'jumlah_sebelum_diskon' => $jumlahSebelumDiskon,
                'jumlah_diskon'         => $jumlahDiskon,
                'jumlah'                => $jumlahAkhir,
                'status'                => $validated['status'],
                'catatan'               => $validated['catatan'] ?? null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | SINKRONISASI PEMASUKAN
            |--------------------------------------------------------------------------
            */
            $pembayaran->refresh();

            if ($pembayaran->status === 'Lunas') {
                Pemasukan::syncFromPembayaran($pembayaran);
            } else {
                Pemasukan::cancelFromPembayaran($pembayaran);
            }


            /*
            |--------------------------------------------------------------------------
            | SINKRONISASI RESERVASI
            |--------------------------------------------------------------------------
            */
            if ($pembayaran->reservasi) {

                $statusReservasi = $pembayaran->reservasi->status;

                // Lunas -> Dikonfirmasi (kecuali sudah Selesai)
                if ($validated['status'] === 'Lunas') {

                    if ($statusReservasi !== 'Selesai') {
                        $pembayaran->reservasi->update(['status' => 'Dikonfirmasi']);
                    }

                }

                // Dibatalkan / Menunggu -> kembali Menunggu
                elseif (in_array($validated['status'], ['Dibatalkan', 'Menunggu'], true)) {

                    if (
                        $statusReservasi !== 'Selesai' &&
                        $statusReservasi !== 'Dibatalkan'
                    ) {
                        $pembayaran->reservasi->update(['status' => 'Menunggu']);
                    }
                }
            }


            DB::commit();


            $pesan = $jumlahDiskon > 0
                ? 'Pembayaran berhasil diperbarui. Diskon promo Rp ' .
                  number_format($jumlahDiskon, 0, ',', '.') .
                  ' berhasil diterapkan.'
                : 'Data pembayaran berhasil diperbarui.';


            return redirect()
                ->route('pembayaran.show', $pembayaran->id)
                ->with('success', $pesan);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal mengupdate pembayaran ID ' . $id . ': ' . $e->getMessage(),
                [
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Data pembayaran gagal diperbarui: ' . $e->getMessage());
        }
    }


    /**
     * Hapus pembayaran
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $pembayaran = Pembayaran::with([
                'reservasi',
                'promo'
            ])->findOrFail($id);

            $reservasi = $pembayaran->reservasi;


            /*
            |--------------------------------------------------------------------------
            | PEMBAYARAN LUNAS TIDAK BOLEH DIHAPUS
            |--------------------------------------------------------------------------
            */
            if ($pembayaran->status === 'Lunas') {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with('error', 'Pembayaran yang sudah Lunas tidak dapat dihapus.');
            }


            // Batalkan pemasukan otomatis sebelum menghapus pembayaran.
            Pemasukan::cancelFromPembayaran($pembayaran);

            $pembayaran->delete();


            if (
                $reservasi &&
                $reservasi->status !== 'Selesai' &&
                $reservasi->status !== 'Dibatalkan'
            ) {
                $reservasi->update(['status' => 'Menunggu']);
            }


            DB::commit();


            return redirect()
                ->route('pembayaran.index')
                ->with('success', 'Data pembayaran berhasil dihapus dan status reservasi disesuaikan.');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal menghapus pembayaran ID ' . $id . ': ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->with('error', 'Data pembayaran gagal dihapus.');
        }
    }


    /**
     * Laporan PDF pembayaran
     */
    public function laporanPdf(Request $request)
    {
        try {

            $query = Pembayaran::with([
                'reservasi.pelanggan',
                'reservasi.perawatan',
                'reservasi.perawatans',
                'reservasi.pegawai',
                'promo.perawatan'
            ]);


            // =========================
            // FILTER STATUS
            // =========================
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }


            // =========================
            // FILTER TANGGAL
            // =========================
            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal_bayar', $request->tanggal);
            }


            // =========================
            // DATA
            // =========================
            $data = $query
                ->orderBy('tanggal_bayar', 'desc')
                ->orderBy('id', 'desc')
                ->get();


            // =========================
            // NOMOR LAPORAN
            // =========================
            $noLaporan = 'LAP-PBY-' . Carbon::now()->format('YmdHis');


            // =========================
            // PDF
            // =========================
            $pdf = Pdf::loadView(
                'pages.pembayaran.laporan',
                compact('data', 'noLaporan')
            );


            return $pdf
                ->setPaper('a4', 'landscape')
                ->download(
                    'laporan-pembayaran-' . Carbon::now()->format('Ymd') . '.pdf'
                );

        } catch (\Throwable $e) {

            Log::error(
                'Gagal membuat laporan pembayaran: ' . $e->getMessage(),
                [
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            return redirect()
                ->back()
                ->with('error', 'Laporan pembayaran gagal dibuat: ' . $e->getMessage());
        }
    }
}