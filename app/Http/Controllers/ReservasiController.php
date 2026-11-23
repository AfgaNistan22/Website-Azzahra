<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservasiStoreRequest;
use App\Http\Requests\ReservasiUpdateRequest;
use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\Perawatan;
use App\Models\Pegawai;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Services\PenjadwalanReservasi;

class ReservasiController extends Controller
{
    /**
     * =====================================================
     * KONFIGURASI
     * =====================================================
     */

    /**
     * Toleransi bentrok dalam menit.
     *
     * 15 = overlap sampai 15 menit masih ditoleransi.
     *
     * Jika ingin benar-benar ZERO OVERLAPPING,
     * ubah menjadi:
     *
     * private const TOLERANSI_BENTROK = 0;
     */
    private const TOLERANSI_BENTROK = 15;

    /**
     * Jam operasional salon (disinkronkan dengan PenjadwalanReservasi).
     */
    private const JAM_BUKA = PenjadwalanReservasi::JAM_BUKA;

    private const JAM_TUTUP = PenjadwalanReservasi::JAM_TUTUP;


    /**
     * =====================================================
     * INDEX
     * =====================================================
     */
    public function index(Request $request)
    {
        try {

            $query = Reservasi::with([
                'pelanggan',
                'perawatan',
                'perawatans',
                'pegawai',
                'pembayaran'
            ]);

            /**
             * =================================================
             * SEARCH
             * =================================================
             */
            if ($request->filled('katakunci')) {

                $katakunci = $request->katakunci;

                $query->where(function ($q) use ($katakunci) {

                    /**
                     * Kode reservasi
                     */
                    $q->where(
                        'kode_reservasi',
                        'like',
                        '%' . $katakunci . '%'
                    )

                    /**
                     * Status
                     */
                    ->orWhere(
                        'status',
                        'like',
                        '%' . $katakunci . '%'
                    )

                    /**
                     * Pelanggan
                     */
                    ->orWhereHas(
                        'pelanggan',
                        function ($pelanggan) use ($katakunci) {

                            $pelanggan->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    )

                    /**
                     * Pegawai
                     */
                    ->orWhereHas(
                        'pegawai',
                        function ($pegawai) use ($katakunci) {

                            $pegawai->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    )

                    /**
                     * Perawatan utama
                     */
                    ->orWhereHas(
                        'perawatan',
                        function ($perawatan) use ($katakunci) {

                            $perawatan->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    )

                    /**
                     * Semua perawatan dari pivot
                     */
                    ->orWhereHas(
                        'perawatans',
                        function ($perawatan) use ($katakunci) {

                            $perawatan->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    );
                });
            }


            /**
             * =================================================
             * FILTER STATUS
             * =================================================
             */
            if ($request->filled('status')) {

                $query->where(
                    'status',
                    $request->status
                );
            }


            /**
             * =================================================
             * FILTER TANGGAL
             * =================================================
             */
            if ($request->filled('tanggal')) {

                $query->whereDate(
                    'tanggal',
                    $request->tanggal
                );
            }


            /**
             * =================================================
             * DATA
             * =================================================
             */
            $data = $query
                ->orderBy(
                    'tanggal',
                    'desc'
                )
                ->orderBy(
                    'jam_mulai',
                    'asc'
                )
                ->paginate(10)
                ->withQueryString();


            /**
             * =================================================
             * SUMMARY
             * =================================================
             */
            $totalReservasi =
                Reservasi::count();

            $totalMenunggu =
                Reservasi::where(
                    'status',
                    'Menunggu'
                )->count();

            $totalDikonfirmasi =
                Reservasi::where(
                    'status',
                    'Dikonfirmasi'
                )->count();

            $totalSelesai =
                Reservasi::where(
                    'status',
                    'Selesai'
                )->count();

            $totalDibatalkan =
                Reservasi::where(
                    'status',
                    'Dibatalkan'
                )->count();


            return view(
                'pages.reservasi.index',
                compact(
                    'data',
                    'totalReservasi',
                    'totalMenunggu',
                    'totalDikonfirmasi',
                    'totalSelesai',
                    'totalDibatalkan'
                )
            );

        } catch (\Throwable $e) {

            Log::error(
                'Gagal menampilkan reservasi: ' .
                $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Data reservasi gagal ditampilkan.'
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

            /**
             * Data pelanggan
             */
            $pelanggan =
                Pelanggan::orderBy('nama')
                ->get();


            /**
             * Data perawatan
             */
            $perawatan =
                Perawatan::orderBy('nama')
                ->get();


            /**
             * Pegawai aktif
             */
            $pegawai =
                Pegawai::where(
                    'status',
                    'Aktif'
                )
                ->orderBy('nama')
                ->get();


            return view(
                'pages.reservasi.create',
                compact(
                    'pelanggan',
                    'perawatan',
                    'pegawai'
                )
            );

        } catch (\Throwable $e) {

            Log::error(
                'Gagal membuka form reservasi: ' .
                $e->getMessage()
            );

            return redirect()
                ->route('reservasi.index')
                ->with(
                    'error',
                    'Form reservasi gagal dibuka.'
                );
        }
    }


    /**
     * =====================================================
     * STORE
     * =====================================================
     */
    public function store(
        ReservasiStoreRequest $request
    ) {

        DB::beginTransaction();

        try {

            $validated =
                $request->validated();


            /**
             * =================================================
             * NORMALISASI PERAWATAN
             * =================================================
             */
            $perawatanIdsInput =
                $validated['perawatan_ids'] ?? [];


            if (
                !is_array($perawatanIdsInput) ||
                empty($perawatanIdsInput)
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Minimal pilih satu perawatan.'
                    );
            }


            $jumlahPerawatan =
                $validated['jumlah_perawatan']
                ?? [];


            /**
             * Ambil ID dari KEY array.
             */
            $perawatanIds = [];

            foreach (
                $perawatanIdsInput
                as $key => $value
            ) {

                /**
                 * Format associative:
                 *
                 * perawatan_ids[5] = 1
                 */
                if (
                    is_numeric($key) &&
                    (int) $key > 0
                ) {

                    $perawatanIds[] =
                        (int) $key;
                }

                /**
                 * Dukungan format indexed:
                 *
                 * perawatan_ids[] = 5
                 */
                elseif (
                    is_numeric($value) &&
                    (int) $value > 0
                ) {

                    $perawatanIds[] =
                        (int) $value;
                }
            }


            /**
             * Hilangkan duplikat.
             */
            $perawatanIds =
                array_values(
                    array_unique(
                        $perawatanIds
                    )
                );


            if (empty($perawatanIds)) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Perawatan yang dipilih tidak valid.'
                    );
            }


            /**
             * =================================================
             * AMBIL DATA PERAWATAN
             * =================================================
             */
            $perawatan =
                Perawatan::whereIn(
                    'id',
                    $perawatanIds
                )
                ->get()
                ->keyBy('id');


            /**
             * Pastikan semua ID benar-benar ada.
             */
            if (
                $perawatan->count() !==
                count($perawatanIds)
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Salah satu perawatan tidak ditemukan.'
                    );
            }


            /**
             * =================================================
             * HITUNG TOTAL DURASI DAN HARGA
             * =================================================
             */
            $totalDurasi = 0;

            $totalHarga = 0;

            $pivotData = [];


            foreach (
                $perawatanIds
                as $perawatanId
            ) {

                $item =
                    $perawatan->get(
                        $perawatanId
                    );


                /**
                 * Ambil jumlah.
                 */
                if (
                    isset(
                        $jumlahPerawatan[
                            $perawatanId
                        ]
                    )
                ) {

                    $jumlah =
                        (int)
                        $jumlahPerawatan[
                            $perawatanId
                        ];

                } elseif (
                    isset(
                        $perawatanIdsInput[
                            $perawatanId
                        ]
                    )
                    &&
                    is_numeric(
                        $perawatanIdsInput[
                            $perawatanId
                        ]
                    )
                ) {

                    $jumlah =
                        (int)
                        $perawatanIdsInput[
                            $perawatanId
                        ];

                } else {

                    $jumlah = 1;
                }


                /**
                 * Batas jumlah.
                 */
                if ($jumlah < 1) {
                    $jumlah = 1;
                }

                if ($jumlah > 10) {
                    $jumlah = 10;
                }


                $durasi =
                    (int)
                    $item->durasi;

                $harga =
                    (int)
                    $item->harga;


                /**
                 * Total durasi.
                 */
                $totalDurasi +=
                    $durasi * $jumlah;


                /**
                 * Total harga.
                 */
                $totalHarga +=
                    $harga * $jumlah;


                /**
                 * Snapshot perawatan.
                 */
                $pivotData[
                    $perawatanId
                ] = [

                    'jumlah' =>
                        $jumlah,

                    'harga' =>
                        $harga,

                    'durasi' =>
                        $durasi,
                ];
            }


            /**
             * =================================================
             * VALIDASI TOTAL DURASI
             * =================================================
             */
            if ($totalDurasi <= 0) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Total durasi perawatan tidak valid.'
                    );
            }


            /**
             * =================================================
             * JAM MULAI
             * =================================================
             */
            $jamMulai =
                Carbon::createFromFormat(
                    'H:i',
                    $validated['jam_mulai']
                );


            /**
             * =================================================
             * JAM SELESAI
             * =================================================
             */
            $jamSelesai =
                $jamMulai
                    ->copy()
                    ->addMinutes(
                        $totalDurasi
                    );


            /**
             * =================================================
             * JAM OPERASIONAL
             * =================================================
             */
            $jamBuka =
                Carbon::createFromFormat(
                    'H:i',
                    self::JAM_BUKA
                );

            $jamTutup =
                Carbon::createFromFormat(
                    'H:i',
                    self::JAM_TUTUP
                );


            if (
                $jamMulai->lt($jamBuka) ||
                $jamSelesai->gt($jamTutup)
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Reservasi harus berada dalam jam operasional ' .
                        self::JAM_BUKA .
                        ' - ' .
                        self::JAM_TUTUP .
                        '. Total durasi perawatan yang dipilih adalah ' .
                        $totalDurasi .
                        ' menit.'
                    );
            }


            /**
             * =================================================
             * CEK PEGAWAI AKTIF
             * =================================================
             */
            $pegawai =
                Pegawai::where(
                    'id',
                    $validated['pegawai_id']
                )
                ->where(
                    'status',
                    'Aktif'
                )
                ->first();


            if (!$pegawai) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Pegawai yang dipilih tidak aktif.'
                    );
            }


            /**
             * =================================================
             * CEK OVERLAP
             * =================================================
             */
            $overlap =
                $this->ambilReservasiOverlap(
                    $validated['tanggal'],
                    $validated['pegawai_id'],
                    $jamMulai,
                    $jamSelesai
                );


            $bentrokBerat = [];


            foreach (
                $overlap
                as $item
            ) {

                $durasiOverlap =
                    $this->hitungDurasiOverlap(
                        $jamMulai,
                        $jamSelesai,
                        Carbon::parse(
                            $item->jam_mulai
                        ),
                        Carbon::parse(
                            $item->jam_selesai
                        )
                    );


                if (
                    $durasiOverlap >
                    self::TOLERANSI_BENTROK
                ) {

                    $bentrokBerat[] =
                        $item;
                }
            }


            /**
             * =================================================
             * JIKA BENTROK
             * =================================================
             */
            if (!empty($bentrokBerat)) {

                $alternatif =
                    $this->cariSlotAlternatif(
                        $validated['tanggal'],
                        $validated['pegawai_id'],
                        $jamMulai,
                        $jamSelesai
                    );


                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Jadwal bentrok dengan reservasi lain. Silakan pilih jadwal alternatif.'
                    )
                    ->with(
                        'alternatif',
                        $alternatif
                    );
            }


            /**
             * =================================================
             * KODE RESERVASI
             * =================================================
             */
            $kodeReservasi =
                'RSV-' .
                Carbon::parse(
                    $validated['tanggal']
                )->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        uniqid(),
                        -4
                    )
                );


            /**
             * =================================================
             * SIMPAN RESERVASI
             * =================================================
             *
             * perawatan_id diisi dengan perawatan pertama
             * sebagai perawatan utama / kompatibilitas.
             *
             * Semua perawatan tetap disimpan juga ke
             * tabel reservasi_perawatan melalui relasi
             * perawatans().
             */
            $reservasi =
                Reservasi::create([

                    'kode_reservasi' =>
                        $kodeReservasi,

                    'pelanggan_id' =>
                        $validated['pelanggan_id'],

                    /**
                     * PERBAIKAN UTAMA:
                     * Kolom ini wajib karena sekarang
                     * ada di tabel reservasi.
                     */
                    'perawatan_id' =>
                        $perawatanIds[0],

                    'pegawai_id' =>
                        $validated['pegawai_id'],

                    'tanggal' =>
                        $validated['tanggal'],

                    'jam_mulai' =>
                        $jamMulai->format(
                            'H:i:s'
                        ),

                    'jam_selesai' =>
                        $jamSelesai->format(
                            'H:i:s'
                        ),

                    /**
                     * Status awal.
                     */
                    'status' =>
                        'Menunggu',

                    'catatan' =>
                        $validated['catatan']
                        ?? null,
                ]);


            /**
             * =================================================
             * SIMPAN DETAIL SEMUA PERAWATAN
             * =================================================
             */
            $reservasi
                ->perawatans()
                ->sync(
                    $pivotData
                );


            /**
             * =================================================
             * BUAT PEMBAYARAN OTOMATIS
             * =================================================
             */
            Pembayaran::create([
                'reservasi_id' =>
                    $reservasi->id,

                'tanggal_bayar' =>
                    null,

                'metode_pembayaran' =>
                    null,

                'jumlah' =>
                    $totalHarga,

                'status' =>
                    'Menunggu',

                'catatan' =>
                    null,
            ]);


            /**
             * =================================================
             * UPDATE DATA KEDATANGAN PELANGGAN
             * =================================================
             */
            $pelangganReservasi =
                Pelanggan::lockForUpdate()
                    ->findOrFail(
                        $validated['pelanggan_id']
                    );

            $tanggalReservasi =
                Carbon::parse(
                    $validated['tanggal']
                )->startOfDay();

            /**
             * Jumlah kedatangan ditambah 1.
             */
            $pelangganReservasi->jumlah_kedatangan =
                (int) $pelangganReservasi->jumlah_kedatangan + 1;

            /**
             * Terakhir datang hanya diganti jika
             * tanggal reservasi lebih baru.
             */
            if (
                !$pelangganReservasi->terakhir_datang ||
                Carbon::parse(
                    $pelangganReservasi->terakhir_datang
                )->startOfDay()->lt($tanggalReservasi)
            ) {

                $pelangganReservasi->terakhir_datang =
                    $tanggalReservasi->format('Y-m-d');
            }

            $pelangganReservasi->save();


            /**
             * =================================================
             * COMMIT
             * =================================================
             */
            DB::commit();


            return redirect()
                ->route(
                    'reservasi.show',
                    $reservasi->id
                )
                ->with(
                    'success',
                    'Reservasi berhasil ditambahkan dan menunggu pembayaran.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal menyimpan reservasi: ' .
                $e->getMessage(),
                [
                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),

                    'trace' =>
                        $e->getTraceAsString()
                ]
            );


            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Reservasi gagal disimpan: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * =====================================================
     * SHOW
     * =====================================================
     */
    public function show(string $id)
    {
        try {

            $data =
                Reservasi::with([
                    'pelanggan',
                    'perawatan',
                    'perawatans',
                    'pegawai',
                    'pembayaran'
                ])
                ->findOrFail($id);


            return view(
                'pages.reservasi.detail',
                compact('data')
            );


        } catch (\Throwable $e) {

            Log::error(
                'Gagal menampilkan detail reservasi ID ' .
                $id .
                ': ' .
                $e->getMessage()
            );


            return redirect()
                ->route('reservasi.index')
                ->with(
                    'error',
                    'Data reservasi tidak ditemukan.'
                );
        }
    }


    /**
     * =====================================================
     * EDIT
     * =====================================================
     */
    public function edit(string $id)
    {
        try {

            /**
             * Ambil reservasi beserta pembayaran.
             */
            $data =
                Reservasi::with([
                    'pelanggan',
                    'perawatan',
                    'perawatans',
                    'pegawai',
                    'pembayaran'
                ])
                ->findOrFail($id);


            /**
             * =================================================
             * CEK STATUS RESERVASI
             * =================================================
             */
            if (
                $data->status === 'Dikonfirmasi' ||
                $data->status === 'Selesai' ||
                $data->status === 'Dibatalkan' ||
                optional($data->pembayaran)->status === 'Lunas'
            ) {

                return redirect()
                    ->route(
                        'reservasi.show',
                        $data->id
                    )
                    ->with(
                        'error',
                        'Reservasi yang sudah dikonfirmasi atau dibayar lunas tidak dapat diedit.'
                    );
            }


            /**
             * =================================================
             * PELANGGAN
             * =================================================
             */
            $pelanggan =
                Pelanggan::orderBy('nama')
                ->get();


            /**
             * =================================================
             * PERAWATAN
             * =================================================
             */
            $perawatan =
                Perawatan::orderBy('nama')
                ->get();


            /**
             * =================================================
             * PEGAWAI
             * =================================================
             */
            $pegawai =
                Pegawai::where(
                    'status',
                    'Aktif'
                )
                ->orWhere(
                    'id',
                    $data->pegawai_id
                )
                ->orderBy('nama')
                ->get();


            return view(
                'pages.reservasi.edit',
                compact(
                    'data',
                    'pelanggan',
                    'perawatan',
                    'pegawai'
                )
            );


        } catch (\Throwable $e) {

            Log::error(
                'Gagal membuka edit reservasi ID ' .
                $id .
                ': ' .
                $e->getMessage()
            );


            return redirect()
                ->route(
                    'reservasi.index'
                )
                ->with(
                    'error',
                    'Data reservasi tidak ditemukan.'
                );
        }
    }


    /**
     * =====================================================
     * UPDATE
     * =====================================================
     */
    public function update(
        ReservasiUpdateRequest $request,
        string $id
    ) {

        DB::beginTransaction();

        try {

            /**
             * =================================================
             * AMBIL RESERVASI
             * =================================================
             */
            $reservasi =
                Reservasi::with([
                    'perawatan',
                    'perawatans',
                    'pembayaran'
                ])
                ->findOrFail($id);


            /**
             * =================================================
             * CEK STATUS SEBELUM UPDATE
             * =================================================
             */
            if (
                $reservasi->status === 'Dikonfirmasi' ||
                $reservasi->status === 'Selesai' ||
                $reservasi->status === 'Dibatalkan' ||
                optional($reservasi->pembayaran)->status === 'Lunas'
            ) {

                DB::rollBack();

                return redirect()
                    ->route(
                        'reservasi.show',
                        $reservasi->id
                    )
                    ->with(
                        'error',
                        'Reservasi yang sudah dikonfirmasi atau dibayar lunas tidak dapat diedit.'
                    );
            }


            /**
             * =================================================
             * VALIDASI
             * =================================================
             */
            $validated =
                $request->validated();


            /**
             * =================================================
             * NORMALISASI PERAWATAN
             * =================================================
             */
            $perawatanIdsInput =
                $validated['perawatan_ids'] ?? [];


            if (
                !is_array($perawatanIdsInput) ||
                empty($perawatanIdsInput)
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Minimal pilih satu perawatan.'
                    );
            }


            $jumlahPerawatan =
                $validated['jumlah_perawatan']
                ?? [];


            /**
             * Ambil ID perawatan.
             */
            $perawatanIds = [];


            foreach (
                $perawatanIdsInput
                as $key => $value
            ) {

                /**
                 * Format:
                 *
                 * perawatan_ids[5] = 1
                 */
                if (
                    is_numeric($key) &&
                    (int) $key > 0
                ) {

                    $perawatanIds[] =
                        (int) $key;

                }

                /**
                 * Format:
                 *
                 * perawatan_ids[] = 5
                 */
                elseif (
                    is_numeric($value) &&
                    (int) $value > 0
                ) {

                    $perawatanIds[] =
                        (int) $value;
                }
            }


            /**
             * Hilangkan duplikat.
             */
            $perawatanIds =
                array_values(
                    array_unique(
                        $perawatanIds
                    )
                );


            if (empty($perawatanIds)) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Perawatan yang dipilih tidak valid.'
                    );
            }


            /**
             * =================================================
             * AMBIL DATA PERAWATAN
             * =================================================
             */
            $perawatan =
                Perawatan::whereIn(
                    'id',
                    $perawatanIds
                )
                ->get()
                ->keyBy('id');


            if (
                $perawatan->count() !==
                count($perawatanIds)
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Perawatan yang dipilih tidak ditemukan.'
                    );
            }


            /**
             * =================================================
             * HITUNG TOTAL DURASI DAN HARGA
             * =================================================
             */
            $totalDurasi = 0;

            $totalHarga = 0;

            $pivotData = [];


            foreach (
                $perawatanIds
                as $perawatanId
            ) {

                $item =
                    $perawatan->get(
                        $perawatanId
                    );


                /**
                 * Ambil jumlah.
                 */
                if (
                    isset(
                        $jumlahPerawatan[
                            $perawatanId
                        ]
                    )
                ) {

                    $jumlah =
                        (int)
                        $jumlahPerawatan[
                            $perawatanId
                        ];

                } elseif (
                    isset(
                        $perawatanIdsInput[
                            $perawatanId
                        ]
                    )
                    &&
                    is_numeric(
                        $perawatanIdsInput[
                            $perawatanId
                        ]
                    )
                ) {

                    $jumlah =
                        (int)
                        $perawatanIdsInput[
                            $perawatanId
                        ];

                } else {

                    $jumlah = 1;
                }


                /**
                 * Batas quantity.
                 */
                if ($jumlah < 1) {
                    $jumlah = 1;
                }

                if ($jumlah > 10) {
                    $jumlah = 10;
                }


                $durasi =
                    (int)
                    $item->durasi;

                $harga =
                    (int)
                    $item->harga;


                /**
                 * Total durasi.
                 */
                $totalDurasi +=
                    $durasi * $jumlah;


                /**
                 * Total harga.
                 */
                $totalHarga +=
                    $harga * $jumlah;


                /**
                 * Snapshot.
                 */
                $pivotData[
                    $perawatanId
                ] = [

                    'jumlah' =>
                        $jumlah,

                    'harga' =>
                        $harga,

                    'durasi' =>
                        $durasi,
                ];
            }


            /**
             * =================================================
             * VALIDASI TOTAL DURASI
             * =================================================
             */
            if ($totalDurasi <= 0) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Total durasi perawatan tidak valid.'
                    );
            }


            /**
             * =================================================
             * JAM MULAI
             * =================================================
             */
            $jamMulai =
                Carbon::createFromFormat(
                    'H:i',
                    $validated['jam_mulai']
                );


            /**
             * =================================================
             * JAM SELESAI
             * =================================================
             */
            $jamSelesai =
                $jamMulai
                    ->copy()
                    ->addMinutes(
                        $totalDurasi
                    );


            /**
             * =================================================
             * JAM OPERASIONAL
             * =================================================
             */
            $jamBuka =
                Carbon::createFromFormat(
                    'H:i',
                    self::JAM_BUKA
                );

            $jamTutup =
                Carbon::createFromFormat(
                    'H:i',
                    self::JAM_TUTUP
                );


            if (
                $jamMulai->lt($jamBuka) ||
                $jamSelesai->gt($jamTutup)
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Reservasi harus berada dalam jam operasional ' .
                        self::JAM_BUKA .
                        ' - ' .
                        self::JAM_TUTUP .
                        '. Total durasi adalah ' .
                        $totalDurasi .
                        ' menit.'
                    );
            }


            /**
             * =================================================
             * CEK PEGAWAI
             * =================================================
             */
            $pegawai =
                Pegawai::where(
                    'id',
                    $validated['pegawai_id']
                )
                ->where(function ($query) use (
                    $reservasi
                ) {

                    $query->where(
                        'status',
                        'Aktif'
                    )
                    ->orWhere(
                        'id',
                        $reservasi->pegawai_id
                    );
                })
                ->first();


            if (!$pegawai) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Pegawai yang dipilih tidak tersedia.'
                    );
            }


            /**
             * =================================================
             * CEK OVERLAP
             * =================================================
             */
            $overlap =
                $this->ambilReservasiOverlap(
                    $validated['tanggal'],
                    $validated['pegawai_id'],
                    $jamMulai,
                    $jamSelesai,
                    $reservasi->id
                );


            $bentrokBerat = [];


            foreach (
                $overlap
                as $item
            ) {

                $durasiOverlap =
                    $this->hitungDurasiOverlap(
                        $jamMulai,
                        $jamSelesai,
                        Carbon::parse(
                            $item->jam_mulai
                        ),
                        Carbon::parse(
                            $item->jam_selesai
                        )
                    );


                if (
                    $durasiOverlap >
                    self::TOLERANSI_BENTROK
                ) {

                    $bentrokBerat[] =
                        $item;
                }
            }


            /**
             * =================================================
             * JIKA BENTROK
             * =================================================
             */
            if (!empty($bentrokBerat)) {

                $alternatif =
                    $this->cariSlotAlternatif(
                        $validated['tanggal'],
                        $validated['pegawai_id'],
                        $jamMulai,
                        $jamSelesai,
                        $reservasi->id
                    );


                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Jadwal bentrok dengan reservasi lain. Silakan pilih jadwal alternatif.'
                    )
                    ->with(
                        'alternatif',
                        $alternatif
                    );
            }


            /**
             * =================================================
             * UPDATE RESERVASI
             * =================================================
             *
             * perawatan_id diisi dengan perawatan pertama
             * sebagai perawatan utama / kompatibilitas.
             *
             * Semua perawatan tetap disimpan juga ke
             * tabel reservasi_perawatan.
             */
            $reservasi->update([

                'pelanggan_id' =>
                    $validated['pelanggan_id'],

                /**
                 * PERBAIKAN UTAMA:
                 * Kolom perawatan_id sekarang wajib diisi.
                 */
                'perawatan_id' =>
                    $perawatanIds[0],

                'pegawai_id' =>
                    $validated['pegawai_id'],

                'tanggal' =>
                    $validated['tanggal'],

                'jam_mulai' =>
                    $jamMulai->format(
                        'H:i:s'
                    ),

                'jam_selesai' =>
                    $jamSelesai->format(
                        'H:i:s'
                    ),

                'status' =>
                    $validated['status'],

                'catatan' =>
                    $validated['catatan']
                    ?? null,
            ]);


            /**
             * =================================================
             * UPDATE DETAIL PERAWATAN
             * =================================================
             */
            $reservasi
                ->perawatans()
                ->sync(
                    $pivotData
                );


            /**
             * =================================================
             * UPDATE NOMINAL PEMBAYARAN
             * =================================================
             */
            $pembayaran =
                Pembayaran::where(
                    'reservasi_id',
                    $reservasi->id
                )
                ->where(
                    'status',
                    'Menunggu'
                )
                ->first();


            if ($pembayaran) {

                $pembayaran->update([
                    'jumlah' =>
                        $totalHarga,
                ]);
            }


            /**
             * =================================================
             * COMMIT
             * =================================================
             */
            DB::commit();


            return redirect()
                ->route(
                    'reservasi.show',
                    $reservasi->id
                )
                ->with(
                    'success',
                    'Data reservasi berhasil diperbarui.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal mengupdate reservasi ID ' .
                $id .
                ': ' .
                $e->getMessage(),
                [
                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),

                    'trace' =>
                        $e->getTraceAsString()
                ]
            );


            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Data reservasi gagal diperbarui: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * =====================================================
     * DELETE
     * =====================================================
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {

            /**
             * Ambil reservasi beserta pembayaran.
             */
            $reservasi =
                Reservasi::with('pembayaran')
                ->findOrFail($id);


            /**
             * =================================================
             * CEK STATUS SEBELUM HAPUS
             * =================================================
             */
            if (
                $reservasi->status === 'Dikonfirmasi' ||
                $reservasi->status === 'Selesai' ||
                $reservasi->status === 'Dibatalkan' ||
                optional($reservasi->pembayaran)->status === 'Lunas'
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Reservasi yang sudah dikonfirmasi atau dibayar lunas tidak dapat dihapus.'
                    );
            }


            /**
             * =================================================
             * HAPUS RESERVASI
             * =================================================
             */
            $reservasi->delete();


            DB::commit();


            return redirect()
                ->route(
                    'reservasi.index'
                )
                ->with(
                    'success',
                    'Data reservasi berhasil dihapus.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal menghapus reservasi ID ' .
                $id .
                ': ' .
                $e->getMessage()
            );


            return redirect()
                ->back()
                ->with(
                    'error',
                    'Data reservasi gagal dihapus.'
                );
        }
    }


    /**
     * =====================================================
     * KONFIRMASI
     * =====================================================
     */
    public function konfirmasi(string $id)
    {
        DB::beginTransaction();

        try {

            $reservasi =
                Reservasi::with([
                    'pembayaran',
                    'pegawai'
                ])
                ->findOrFail($id);


            /**
             * Hanya Menunggu.
             */
            if (
                $reservasi->status !==
                'Menunggu'
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Hanya reservasi dengan status Menunggu yang dapat dikonfirmasi.'
                    );
            }


            /**
             * Cek pembayaran.
             */
            if (
                !$reservasi->pembayaran ||
                $reservasi->pembayaran->status !== 'Lunas'
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Reservasi belum dapat dikonfirmasi karena pembayaran belum lunas.'
                    );
            }


            /**
             * Cek pegawai.
             */
            if (
                $reservasi->pegawai &&
                $reservasi->pegawai->status !==
                'Aktif'
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Pegawai pada reservasi tidak aktif.'
                    );
            }


            /**
             * Jam reservasi.
             */
            $jamMulai =
                Carbon::parse(
                    $reservasi->jam_mulai
                );

            $jamSelesai =
                Carbon::parse(
                    $reservasi->jam_selesai
                );


            /**
             * =================================================
             * CEK OVERLAP LAGI
             * =================================================
             */
            $overlap =
                $this->ambilReservasiOverlap(
                    $reservasi->tanggal->format(
                        'Y-m-d'
                    ),
                    $reservasi->pegawai_id,
                    $jamMulai,
                    $jamSelesai,
                    $reservasi->id
                );


            $bentrokBerat = [];


            foreach (
                $overlap
                as $item
            ) {

                $durasiOverlap =
                    $this->hitungDurasiOverlap(
                        $jamMulai,
                        $jamSelesai,
                        Carbon::parse(
                            $item->jam_mulai
                        ),
                        Carbon::parse(
                            $item->jam_selesai
                        )
                    );


                if (
                    $durasiOverlap >
                    self::TOLERANSI_BENTROK
                ) {

                    $bentrokBerat[] =
                        $item;
                }
            }


            /**
             * Jika masih bentrok.
             */
            if (!empty($bentrokBerat)) {

                $alternatif =
                    $this->cariSlotAlternatif(
                        $reservasi->tanggal->format(
                            'Y-m-d'
                        ),
                        $reservasi->pegawai_id,
                        $jamMulai,
                        $jamSelesai,
                        $reservasi->id
                    );


                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Reservasi tidak dapat dikonfirmasi karena terdapat bentrok jadwal.'
                    )
                    ->with(
                        'alternatif',
                        $alternatif
                    );
            }


            /**
             * =================================================
             * UPDATE STATUS
             * =================================================
             */
            $reservasi->update([
                'status' =>
                    'Dikonfirmasi'
            ]);


            DB::commit();


            return redirect()
                ->back()
                ->with(
                    'success',
                    'Reservasi berhasil dikonfirmasi.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal mengkonfirmasi reservasi ID ' .
                $id .
                ': ' .
                $e->getMessage()
            );


            return redirect()
                ->back()
                ->with(
                    'error',
                    'Reservasi gagal dikonfirmasi.'
                );
        }
    }


    /**
     * =====================================================
     * SELESAI
     * =====================================================
     */
    public function selesai(string $id)
    {
        DB::beginTransaction();

        try {

            $reservasi =
                Reservasi::findOrFail($id);


            /**
             * Hanya reservasi Dikonfirmasi.
             */
            if (
                $reservasi->status !==
                'Dikonfirmasi'
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Hanya reservasi yang sudah dikonfirmasi yang dapat diselesaikan.'
                    );
            }


            $reservasi->update([
                'status' =>
                    'Selesai'
            ]);


            DB::commit();


            return redirect()
                ->back()
                ->with(
                    'success',
                    'Reservasi berhasil ditandai sebagai selesai.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal menyelesaikan reservasi ID ' .
                $id .
                ': ' .
                $e->getMessage()
            );


            return redirect()
                ->back()
                ->with(
                    'error',
                    'Reservasi gagal diselesaikan.'
                );
        }
    }


    /**
     * =====================================================
     * BATAL
     * =====================================================
     */
    public function batal(string $id)
    {
        DB::beginTransaction();

        try {

            $reservasi =
                Reservasi::with('pembayaran')
                ->findOrFail($id);


            /**
             * =================================================
             * RESERVASI SELESAI
             * =================================================
             */
            if (
                $reservasi->status ===
                'Selesai'
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Reservasi yang sudah selesai tidak dapat dibatalkan.'
                    );
            }


            /**
             * =================================================
             * RESERVASI SUDAH LUNAS
             * =================================================
             */
            if (
                $reservasi->status === 'Dikonfirmasi' ||
                optional($reservasi->pembayaran)->status === 'Lunas'
            ) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Reservasi yang sudah dikonfirmasi atau dibayar lunas tidak dapat dibatalkan melalui proses ini.'
                    );
            }


            /**
             * =================================================
             * UPDATE STATUS
             * =================================================
             */
            $reservasi->update([
                'status' =>
                    'Dibatalkan'
            ]);


            /**
             * Jika pembayaran masih Menunggu,
             * ikut ubah menjadi Dibatalkan.
             */
            Pembayaran::where(
                'reservasi_id',
                $reservasi->id
            )
            ->where(
                'status',
                'Menunggu'
            )
            ->update([
                'status' =>
                    'Dibatalkan'
            ]);


            DB::commit();


            return redirect()
                ->back()
                ->with(
                    'success',
                    'Reservasi berhasil dibatalkan.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Gagal membatalkan reservasi ID ' .
                $id .
                ': ' .
                $e->getMessage()
            );


            return redirect()
                ->back()
                ->with(
                    'error',
                    'Reservasi gagal dibatalkan.'
                );
        }
    }


    /**
     * =====================================================
     * AMBIL RESERVASI OVERLAP
     * =====================================================
     */
    private function ambilReservasiOverlap(
        $tanggal,
        $pegawaiId,
        Carbon $jamMulai,
        Carbon $jamSelesai,
        $excludeId = null
    ) {

        $query =
            Reservasi::with([
                'pelanggan',
                'perawatan',
                'perawatans',
                'pegawai'
            ])
            ->whereDate(
                'tanggal',
                $tanggal
            )
            ->where(
                'pegawai_id',
                $pegawaiId
            )
            ->where(
                'status',
                '!=',
                'Dibatalkan'
            )
            ->where(function ($q) use (
                $jamMulai,
                $jamSelesai
            ) {

                $q->where(
                    'jam_mulai',
                    '<',
                    $jamSelesai->format(
                        'H:i:s'
                    )
                )
                ->where(
                    'jam_selesai',
                    '>',
                    $jamMulai->format(
                        'H:i:s'
                    )
                );
            });


        /**
         * Jangan bandingkan dengan dirinya sendiri
         * ketika UPDATE / KONFIRMASI.
         */
        if ($excludeId) {

            $query->where(
                'id',
                '!=',
                $excludeId
            );
        }


        return $query
            ->orderBy(
                'jam_mulai'
            )
            ->get();
    }


    /**
     * =====================================================
     * HITUNG DURASI OVERLAP
     * =====================================================
     */
    private function hitungDurasiOverlap(
        Carbon $startA,
        Carbon $endA,
        Carbon $startB,
        Carbon $endB
    ): int {

        /**
         * Ambil waktu mulai overlap
         * yang paling akhir.
         */
        $mulaiOverlap =
            $startA->greaterThan(
                $startB
            )
                ? $startA
                : $startB;


        /**
         * Ambil waktu selesai overlap
         * yang paling awal.
         */
        $selesaiOverlap =
            $endA->lessThan(
                $endB
            )
                ? $endA
                : $endB;


        /**
         * Tidak overlap.
         */
        if (
            $mulaiOverlap
                ->greaterThanOrEqualTo(
                    $selesaiOverlap
                )
        ) {

            return 0;
        }


        /**
         * Durasi overlap dalam menit.
         */
        return $mulaiOverlap->diffInMinutes(
            $selesaiOverlap
        );
    }


    /**
     * =====================================================
     * CARI SLOT ALTERNATIF
     * =====================================================
     */
    private function cariSlotAlternatif(
        $tanggal,
        $pegawaiId,
        Carbon $jamMulai,
        Carbon $jamSelesai,
        $excludeId = null
    ) {

        /**
         * Durasi reservasi.
         */
        $durasi =
            $jamMulai->diffInMinutes(
                $jamSelesai
            );


        /**
         * Ambil semua reservasi pegawai
         * pada tanggal yang sama.
         */
        $reservasi =
            Reservasi::whereDate(
                'tanggal',
                $tanggal
            )
            ->where(
                'pegawai_id',
                $pegawaiId
            )
            ->where(
                'status',
                '!=',
                'Dibatalkan'
            )
            ->when(
                $excludeId,
                function ($query) use (
                    $excludeId
                ) {

                    $query->where(
                        'id',
                        '!=',
                        $excludeId
                    );
                }
            )
            ->orderBy(
                'jam_mulai'
            )
            ->get();


        /**
         * Jam buka.
         */
        $jamBuka =
            Carbon::createFromFormat(
                'H:i',
                self::JAM_BUKA
            );


        /**
         * Jam tutup.
         */
        $jamTutup =
            Carbon::createFromFormat(
                'H:i',
                self::JAM_TUTUP
            );


        /**
         * Array kandidat.
         */
        $kandidat = [];


        /**
         * Cursor dimulai dari jam buka.
         */
        $cursor =
            $jamBuka->copy();


        /**
         * Cari setiap 15 menit.
         */
        while (
            $cursor
                ->copy()
                ->addMinutes(
                    $durasi
                )
                ->lessThanOrEqualTo(
                    $jamTutup
                )
        ) {

            /**
             * Kandidat mulai.
             */
            $candidateStart =
                $cursor->copy();


            /**
             * Kandidat selesai.
             */
            $candidateEnd =
                $candidateStart
                    ->copy()
                    ->addMinutes(
                        $durasi
                    );


            /**
             * Total overlap.
             */
            $totalOverlap = 0;


            foreach (
                $reservasi
                as $item
            ) {

                $totalOverlap +=
                    $this->hitungDurasiOverlap(
                        $candidateStart,
                        $candidateEnd,
                        Carbon::parse(
                            $item->jam_mulai
                        ),
                        Carbon::parse(
                            $item->jam_selesai
                        )
                    );
            }


            /**
             * Kandidat yang overlap
             * masih berada dalam toleransi.
             */
            if (
                $totalOverlap <=
                self::TOLERANSI_BENTROK
            ) {

                /**
                 * Jarak kandidat dari
                 * waktu yang diminta.
                 */
                $jarak =
                    abs(
                        $candidateStart
                            ->diffInMinutes(
                                $jamMulai,
                                false
                            )
                    );


                $kandidat[] = [

                    'tanggal' =>
                        $tanggal,

                    'jam_mulai' =>
                        $candidateStart
                            ->format('H:i'),

                    'jam_selesai' =>
                        $candidateEnd
                            ->format('H:i'),

                    'overlap' =>
                        $totalOverlap,

                    'jarak' =>
                        $jarak,
                ];
            }


            /**
             * Geser 15 menit.
             */
            $cursor->addMinutes(15);
        }


        /**
         * =================================================
         * SORT KANDIDAT
         * =================================================
         */
        usort(
            $kandidat,
            function ($a, $b) {

                if (
                    $a['overlap'] !==
                    $b['overlap']
                ) {

                    return
                        $a['overlap']
                        <=>
                        $b['overlap'];
                }


                return
                    $a['jarak']
                    <=>
                    $b['jarak'];
            }
        );


        /**
         * Ambil maksimal 5 alternatif.
         */
        return array_slice(
            $kandidat,
            0,
            5
        );
    }


    /**
     * =====================================================
     * CEK JADWAL / REKOMENDASI JADWAL
     * =====================================================
     */
    public function cekJadwal(Request $request)
    {
        try {

            $validated = $request->validate([
                'tanggal' =>
                    'required|date',

                'pegawai_id' =>
                    'required|exists:pegawai,id',

                'jam_mulai' =>
                    'required|date_format:H:i',

                'jam_selesai' =>
                    'required|date_format:H:i',

                'exclude_id' =>
                    'nullable|integer|exists:reservasi,id',
            ]);


            $tanggal =
                $validated['tanggal'];

            $pegawaiId =
                (int) $validated['pegawai_id'];

            $excludeId =
                $validated['exclude_id']
                ?? null;


            $jamMulai =
                Carbon::createFromFormat(
                    'H:i',
                    $validated['jam_mulai']
                );

            $jamSelesai =
                Carbon::createFromFormat(
                    'H:i',
                    $validated['jam_selesai']
                );


            if (
                !$jamSelesai->gt(
                    $jamMulai
                )
            ) {

                return response()->json([
                    'status' =>
                        'error',

                    'message' =>
                        'Jam selesai harus lebih besar dari jam mulai.',

                    'wait_minutes' =>
                        0,

                    'reservasi_berbenturan' =>
                        null,

                    'rekomendasi' =>
                        [],
                ], 422);
            }


            $jamBuka =
                Carbon::createFromFormat(
                    'H:i',
                    self::JAM_BUKA
                );

            $jamTutup =
                Carbon::createFromFormat(
                    'H:i',
                    self::JAM_TUTUP
                );


            if (
                $jamMulai->lt($jamBuka) ||
                $jamSelesai->gt($jamTutup)
            ) {

                return response()->json([
                    'status' =>
                        'error',

                    'message' =>
                        'Jadwal harus berada dalam jam operasional ' .
                        self::JAM_BUKA .
                        ' - ' .
                        self::JAM_TUTUP .
                        '.',

                    'wait_minutes' =>
                        0,

                    'reservasi_berbenturan' =>
                        null,

                    'rekomendasi' =>
                        [],
                ], 422);
            }


            /**
             * Ambil seluruh reservasi pegawai
             * pada tanggal tersebut.
             */
            $reservasi =
                Reservasi::whereDate(
                    'tanggal',
                    $tanggal
                )
                ->where(
                    'pegawai_id',
                    $pegawaiId
                )
                ->where(
                    'status',
                    '!=',
                    'Dibatalkan'
                )
                ->when(
                    $excludeId,
                    function ($query) use (
                        $excludeId
                    ) {

                        $query->where(
                            'id',
                            '!=',
                            $excludeId
                        );
                    }
                )
                ->orderBy(
                    'jam_mulai',
                    'asc'
                )
                ->get([
                    'id',
                    'tanggal',
                    'jam_mulai',
                    'jam_selesai',
                    'status',
                ]);


            $bentrok = [];


            foreach (
                $reservasi
                as $item
            ) {

                $existingStart =
                    Carbon::parse(
                        $item->jam_mulai
                    );

                $existingEnd =
                    Carbon::parse(
                        $item->jam_selesai
                    );


                if (
                    $jamMulai->lt(
                        $existingEnd
                    )
                    &&
                    $jamSelesai->gt(
                        $existingStart
                    )
                ) {

                    $overlapStart =
                        $jamMulai->greaterThan(
                            $existingStart
                        )
                            ? $jamMulai
                            : $existingStart;

                    $overlapEnd =
                        $jamSelesai->lessThan(
                            $existingEnd
                        )
                            ? $jamSelesai
                            : $existingEnd;

                    $overlapMinutes =
                        $overlapStart->diffInMinutes(
                            $overlapEnd
                        );


                    if (
                        $overlapMinutes > 0
                    ) {

                        $bentrok[] = [

                            'id' =>
                                $item->id,

                            'jam_mulai' =>
                                $existingStart
                                    ->format('H:i'),

                            'jam_selesai' =>
                                $existingEnd
                                    ->format('H:i'),

                            'status' =>
                                $item->status,

                            'overlap' =>
                                $overlapMinutes,
                        ];
                    }
                }
            }


            /**
             * Tidak ada bentrok.
             */
            if (
                empty($bentrok)
            ) {

                return response()->json([
                    'status' =>
                        'tersedia',

                    'message' =>
                        'Jadwal tersedia.',

                    'wait_minutes' =>
                        0,

                    'reservasi_berbenturan' =>
                        null,

                    'rekomendasi' =>
                        [],
                ]);
            }


            /**
             * Cari reservasi terakhir yang
             * menyebabkan pelanggan menunggu.
             */
            $reservasiTerakhir =
                null;

            $waktuTunggu = 0;


            foreach (
                $bentrok
                as $item
            ) {

                $end =
                    Carbon::createFromFormat(
                        'H:i',
                        $item['jam_selesai']
                    );


                if (
                    $end->gt(
                        $jamMulai
                    )
                ) {

                    $selisih =
                        $jamMulai->diffInMinutes(
                            $end
                        );


                    if (
                        $selisih >
                        $waktuTunggu
                    ) {

                        $waktuTunggu =
                            $selisih;

                        $reservasiTerakhir =
                            $item;
                    }
                }
            }


            /**
             * Jika overlap maksimal 15 menit,
             * tetap diterima.
             */
            if (
                $waktuTunggu > 0 &&
                $waktuTunggu <=
                self::TOLERANSI_BENTROK
            ) {

                return response()->json([
                    'status' =>
                        'tunggu',

                    'message' =>
                        'Anda akan menunggu ' .
                        $waktuTunggu .
                        ' menit dikarenakan menunggu terapi dari reservasi sebelumnya.',

                    'wait_minutes' =>
                        $waktuTunggu,

                    'reservasi_berbenturan' =>
                        $reservasiTerakhir,

                    'rekomendasi' =>
                        [],
                ]);
            }


            /**
             * Bentrok lebih dari toleransi.
             */
            $rekomendasi =
                $this->cariSlotAlternatifStrict(
                    $tanggal,
                    $pegawaiId,
                    $jamMulai,
                    $jamSelesai,
                    $excludeId
                );


            return response()->json([
                'status' =>
                    'bentrok',

                'message' =>
                    'Jadwal bertabrakan lebih dari ' .
                    self::TOLERANSI_BENTROK .
                    ' menit. Silakan pilih jadwal alternatif.',

                'wait_minutes' =>
                    $waktuTunggu,

                'reservasi_berbenturan' =>
                    $bentrok[0]
                    ?? null,

                'rekomendasi' =>
                    $rekomendasi,
            ]);


        } catch (\Throwable $e) {

            Log::error(
                'Gagal mengecek jadwal reservasi: ' .
                $e->getMessage(),
                [
                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );


            return response()->json([
                'status' =>
                    'error',

                'message' =>
                    'Gagal mengecek ketersediaan jadwal.',

                'wait_minutes' =>
                    0,

                'reservasi_berbenturan' =>
                    null,

                'rekomendasi' =>
                    [],
            ], 500);
        }
    }


    /**
     * =====================================================
     * CARI REKOMENDASI SLOT STRICT
     * =====================================================
     */
    private function cariSlotAlternatifStrict(
        $tanggal,
        $pegawaiId,
        Carbon $jamMulai,
        Carbon $jamSelesai,
        $excludeId = null
    ) {

        $durasi =
            $jamMulai->diffInMinutes(
                $jamSelesai
            );


        $reservasi =
            Reservasi::whereDate(
                'tanggal',
                $tanggal
            )
            ->where(
                'pegawai_id',
                $pegawaiId
            )
            ->where(
                'status',
                '!=',
                'Dibatalkan'
            )
            ->when(
                $excludeId,
                function ($query) use (
                    $excludeId
                ) {

                    $query->where(
                        'id',
                        '!=',
                        $excludeId
                    );
                }
            )
            ->orderBy(
                'jam_mulai',
                'asc'
            )
            ->get([
                'id',
                'jam_mulai',
                'jam_selesai'
            ]);


        $jamBuka =
            Carbon::createFromFormat(
                'H:i',
                self::JAM_BUKA
            );

        $jamTutup =
            Carbon::createFromFormat(
                'H:i',
                self::JAM_TUTUP
            );


        $kandidat = [];


        /**
         * Greedy:
         * scan slot dari jam buka.
         *
         * Step 15 menit.
         */
        $cursor =
            $jamBuka->copy();


        while (
            $cursor
                ->copy()
                ->addMinutes(
                    $durasi
                )
                ->lte(
                    $jamTutup
                )
        ) {

            $candidateStart =
                $cursor->copy();

            $candidateEnd =
                $candidateStart
                    ->copy()
                    ->addMinutes(
                        $durasi
                    );


            $bentrok =
                false;


            foreach (
                $reservasi
                as $item
            ) {

                $existingStart =
                    Carbon::parse(
                        $item->jam_mulai
                    );

                $existingEnd =
                    Carbon::parse(
                        $item->jam_selesai
                    );


                if (
                    $candidateStart->lt(
                        $existingEnd
                    )
                    &&
                    $candidateEnd->gt(
                        $existingStart
                    )
                ) {

                    $bentrok =
                        true;

                    break;
                }
            }


            if (!$bentrok) {

                $kandidat[] = [

                    'tanggal' =>
                        Carbon::parse(
                            $tanggal
                        )->format(
                            'd/m/Y'
                        ),

                    'jam_mulai' =>
                        $candidateStart
                            ->format('H:i'),

                    'jam_selesai' =>
                        $candidateEnd
                            ->format('H:i'),

                    'jarak' =>
                        abs(
                            $candidateStart
                                ->diffInMinutes(
                                    $jamMulai,
                                    false
                                )
                        ),
                ];
            }


            $cursor->addMinutes(15);
        }


        usort(
            $kandidat,
            function ($a, $b) {

                return
                    $a['jarak']
                    <=>
                    $b['jarak'];
            }
        );


        return array_slice(
            $kandidat,
            0,
            3
        );
    }


    /**
     * =====================================================
     * LAPORAN PDF
     * =====================================================
     *
     * REVISI:
     * - Disesuaikan dengan view pages.reservasi.laporan_pdf
     *   (variabel: reservasi, no_laporan, tanggalLaporan,
     *   periode, total, menunggu, dikonfirmasi, selesai,
     *   dibatalkan, alamat_salon, telepon_salon).
     * - Filter katakunci ikut diterapkan (sama seperti index).
     * - Ditambah batas waktu & memori untuk laporan besar.
     * - Error asli ditampilkan saat APP_DEBUG=true.
     */
    public function laporanPdf(Request $request)
    {
        try {

            /**
             * Laporan bisa berisi banyak data.
             */
            @set_time_limit(120);
            @ini_set('memory_limit', '512M');


            /**
             * Nama bulan dalam Bahasa Indonesia.
             */
            Carbon::setLocale('id');


            /**
             * Ambil data lengkap.
             */
            $query =
                Reservasi::with([
                    'pelanggan',
                    'perawatan',
                    'perawatans',
                    'pegawai',
                    'pembayaran'
                ]);


            /**
             * Filter pencarian (sama seperti index).
             */
            if ($request->filled('katakunci')) {

                $katakunci = $request->katakunci;

                $query->where(function ($q) use ($katakunci) {

                    $q->where(
                        'kode_reservasi',
                        'like',
                        '%' . $katakunci . '%'
                    )
                    ->orWhere(
                        'status',
                        'like',
                        '%' . $katakunci . '%'
                    )
                    ->orWhereHas(
                        'pelanggan',
                        function ($pelanggan) use ($katakunci) {

                            $pelanggan->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    )
                    ->orWhereHas(
                        'pegawai',
                        function ($pegawai) use ($katakunci) {

                            $pegawai->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    )
                    ->orWhereHas(
                        'perawatan',
                        function ($perawatan) use ($katakunci) {

                            $perawatan->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    )
                    ->orWhereHas(
                        'perawatans',
                        function ($perawatan) use ($katakunci) {

                            $perawatan->where(
                                'nama',
                                'like',
                                '%' . $katakunci . '%'
                            );
                        }
                    );
                });
            }


            /**
             * Filter status.
             */
            if ($request->filled('status')) {

                $query->where(
                    'status',
                    $request->status
                );
            }


            /**
             * Filter tanggal.
             */
            if ($request->filled('tanggal')) {

                $query->whereDate(
                    'tanggal',
                    $request->tanggal
                );
            }


            /**
             * Data laporan.
             */
            $data =
                $query
                ->orderBy(
                    'tanggal',
                    'desc'
                )
                ->orderBy(
                    'jam_mulai',
                    'asc'
                )
                ->get();


            /**
             * =================================================
             * VARIABEL UNTUK VIEW laporan_pdf
             * =================================================
             */

            // Daftar reservasi (view memakai $reservasi)
            $reservasi = $data;

            // Nomor laporan (view memakai $no_laporan)
            $noLaporan =
                'LAP-RSV-' .
                Carbon::now()->format(
                    'YmdHis'
                );

            $no_laporan = $noLaporan;

            // Tanggal cetak laporan
            $tanggalLaporan =
                Carbon::now()->translatedFormat('d F Y');

            // Periode mengikuti filter tanggal
            $periode = $request->filled('tanggal')
                ? Carbon::parse($request->tanggal)->translatedFormat('d F Y')
                : 'Semua Periode';

            // Ringkasan (dihitung dari data yang tampil di laporan)
            $total        = $data->count();
            $menunggu     = $data->where('status', 'Menunggu')->count();
            $dikonfirmasi = $data->where('status', 'Dikonfirmasi')->count();
            $selesai      = $data->where('status', 'Selesai')->count();
            $dibatalkan   = $data->where('status', 'Dibatalkan')->count();

            // Opsional: tampil di header laporan (boleh dikosongkan)
            $alamat_salon  = null;
            $telepon_salon = null;


            /**
             * Generate PDF.
             */
            $pdf =
                Pdf::loadView(
                    'pages.reservasi.laporan_pdf',
                    compact(
                        'data',
                        'reservasi',
                        'noLaporan',
                        'no_laporan',
                        'tanggalLaporan',
                        'periode',
                        'total',
                        'menunggu',
                        'dikonfirmasi',
                        'selesai',
                        'dibatalkan',
                        'alamat_salon',
                        'telepon_salon'
                    )
                );


            /**
             * Download PDF.
             */
            return $pdf
                ->setPaper(
                    'a4',
                    'landscape'
                )
                ->download(
                    'laporan-reservasi-' .
                    Carbon::now()->format(
                        'Ymd'
                    ) .
                    '.pdf'
                );


        } catch (\Throwable $e) {

            Log::error(
                'Gagal membuat laporan reservasi: ' .
                $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            );


            /**
             * Saat APP_DEBUG=true, tampilkan penyebab asli
             * agar mudah dilacak. Di produksi tetap pesan umum.
             */
            $pesan = config('app.debug')
                ? 'Laporan gagal dibuat: ' . $e->getMessage()
                : 'Laporan reservasi gagal dibuat.';

            return redirect()
                ->back()
                ->with(
                    'error',
                    $pesan
                );
        }
    }
}