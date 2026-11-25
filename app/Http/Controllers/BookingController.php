<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Perawatan;
use App\Models\Promo;
use App\Models\Reservasi;
use App\Services\PelangganAkun;
use App\Services\PenjadwalanReservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Halaman customer: Bookingan Saya, Daftar Booking, Buat Booking, Konfirmasi Pembayaran,
 * Pembatalan, dan Lacak Booking.
 *
 * Tabel `reservasi` tidak punya kolom user_id, jadi pemilik booking dicari
 * lewat data Pelanggan yang tertaut ke akun (pelanggan.user_id).
 *
 * PELANGGAN TANPA AKUN (tamu):
 *  - Mengisi nama + nomor WhatsApp saat booking; dibuatkan data Pelanggan dengan user_id kosong.
 *  - Akses ke booking-nya dicatat di session `booking_tamu` (daftar id reservasi).
 *  - Bila session hilang, akses dipulihkan lewat halaman Lacak (kode booking + nomor WhatsApp).
 *  - Booking tamu ditandai reservasi.sumber = 'tamu' (booking tamu yang tidak dibayar
 *    dibatalkan otomatis oleh scheduler).
 *
 * PROMO:
 *  - Promo hanya muncul bila status Aktif dan hari ini berada di antara tanggal_mulai
 *    dan tanggal_selesai (scope Promo::berlaku()).
 *  - perawatan_id NULL  = promo berlaku untuk SEMUA perawatan (dasar diskon = total seluruh perawatan).
 *  - perawatan_id terisi = promo khusus satu perawatan (dasar diskon = harga x jumlah perawatan itu).
 *  - Besar diskon selalu dihitung ulang di server saat pembayaran disimpan.
 *
 * Aturan penjadwalan (bentrok, toleransi, jam operasional, rekomendasi slot)
 * ada di App\Services\PenjadwalanReservasi, satu aturan yang sama untuk
 * pratinjau jadwal dan penyimpanan.
 */
class BookingController extends Controller
{
    private const STATUS_FILTER = ['Menunggu', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'];

    /** Pembatalan lewat sistem hanya boleh bila jadwal masih lebih dari sekian jam lagi */
    private const BATAS_BATAL_JAM = 3;

    /** Nama kolom nomor telepon di tabel pelanggan */
    private const KOLOM_HP = 'no_telepon';

    private ?Pelanggan $pelangganCache = null;
    private bool $pelangganSudahDicari = false;

    public function __construct(
        private PenjadwalanReservasi $jadwal,
        private PelangganAkun $akun
    ) {
    }


    // ==================================================================
    //  BOOKINGAN SAYA, DAFTAR, DETAIL
    // ==================================================================

    /** GET /booking-saya : booking terdekat yang masih berjalan (wajib login) */
    public function saya()
    {
        $booking = $this->queryMilikSaya()
            ->with(['perawatans.kategori', 'pegawai', 'pembayaran', 'pelanggan'])
            ->whereIn('status', ['Menunggu', 'Dikonfirmasi'])
            // Booking yang tanggalnya sudah lewat tidak ditampilkan sebagai "terdekat"
            ->whereDate('tanggal', '>=', $this->jadwal->sekarang()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->first();

        if ($booking) {
            $this->map($booking);
        }

        return view('pages.booking.saya', [
            'booking'          => $booking,
            'metodePembayaran' => MetodePembayaran::aktif()->get(),
            'kontakSalon'      => $this->kontakSalon(),
        ]);
    }

    /** GET /daftar-booking?status=... (wajib login) */
    public function daftar(Request $request)
    {
        $status = $request->query('status');

        $query = $this->queryMilikSaya()->with(['perawatans', 'pembayaran']);

        if ($status && in_array($status, self::STATUS_FILTER, true)) {
            $query->where('status', $status);
        }

        $bookings = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(10)->withQueryString();
        $bookings->getCollection()->each(fn ($b) => $this->map($b));

        $hitung = $this->queryMilikSaya()
            ->select('status', DB::raw('COUNT(*) AS jml'))
            ->groupBy('status')
            ->pluck('jml', 'status');

        $ringkasan = [
            'semua'    => $hitung->sum(),
            'menunggu' => $hitung['Menunggu'] ?? 0,
            'diproses' => $hitung['Dikonfirmasi'] ?? 0,
            'selesai'  => $hitung['Selesai'] ?? 0,
        ];

        return view('pages.booking.daftar', compact('bookings', 'ringkasan'));
    }

    /** GET /booking/{booking} : detail (pemilik akun ATAU tamu yang punya akses di session) */
    public function detail(Reservasi $booking)
    {
        $this->pastikanMilikSendiri($booking);

        $booking->load(['perawatans.kategori', 'pegawai', 'pembayaran', 'pelanggan']);
        $this->map($booking);

        return view('pages.booking.saya', [
            'booking'          => $booking,
            'metodePembayaran' => MetodePembayaran::aktif()->get(),
            'kontakSalon'      => $this->kontakSalon(),
        ]);
    }


    // ==================================================================
    //  LACAK BOOKING (untuk pelanggan tanpa akun)
    // ==================================================================

    /**
     * GET /booking/lacak
     * Form memakai method GET, jadi pencarian dilakukan di sini.
     * Tanpa parameter kode + nomor WhatsApp, hanya form yang tampil.
     */
    public function lacakForm(Request $request)
    {
        $booking = null;
        $kode    = trim((string) $request->input('kode'));

        if ($request->filled('kode') && $request->filled('no_whatsapp')) {
            $booking = $this->cariBooking($kode, (string) $request->input('no_whatsapp'));

            // Pulihkan akses tamu ke booking ini (supaya detail & pembayaran bisa dibuka)
            if ($booking) {
                $this->catatAksesTamu($request, $booking);
            }
        }

        return view('pages.booking.lacak', compact('booking', 'kode'));
    }

    /** POST /booking/lacak : memakai logika yang sama dengan lacakForm */
    public function lacak(Request $request)
    {
        return $this->lacakForm($request);
    }


    // ==================================================================
    //  KONFIRMASI PEMBAYARAN
    // ==================================================================

    /** GET /booking/{booking}/pembayaran : halaman pilih metode + unggah bukti */
    public function pembayaran(Reservasi $booking)
    {
        $this->pastikanMilikSendiri($booking);

        $booking->load(['perawatans', 'pegawai', 'pembayaran', 'pelanggan']);
        $this->map($booking);

        if (! $booking->perlu_bayar) {
            return redirect()->route('booking.detail', $booking->id)
                ->with('error', 'Booking ini tidak memerlukan pembayaran, atau bukti pembayaran sudah dikirim.');
        }

        // Rapikan data: promo yang sudah lewat tanggal selesai dijadikan Tidak Aktif
        $this->nonaktifkanPromoKedaluwarsa();

        $totalAsli = $this->totalPerawatan($booking);

        return view('pages.booking.pembayaran', [
            'booking'          => $booking,
            'metodePembayaran' => MetodePembayaran::aktif()->get(),
            'totalAsli'        => $totalAsli,
            'promoTersedia'    => $this->promoTersedia($booking, $totalAsli),
        ]);
    }

    public function simpanPembayaran(Request $request, Reservasi $booking)
    {
        $this->pastikanMilikSendiri($booking);

        $booking->load(['perawatans', 'pembayaran']);
        $this->map($booking);

        if (! $booking->perlu_bayar || ! $booking->pembayaran) {
            return redirect()->route('booking.detail', $booking->id)
                ->with('error', 'Booking ini tidak memerlukan pembayaran, atau pembayarannya sudah diproses.');
        }

        $validated = $request->validate([
            'promo_id'             => ['nullable', 'integer'],
            'metode_pembayaran_id' => ['required', 'integer', Rule::exists((new MetodePembayaran)->getTable(), 'id')],
            'bukti_bayar'          => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'catatan'              => ['nullable', 'string', 'max:255'],
        ], [
            'metode_pembayaran_id.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'bukti_bayar.required'          => 'Unggah bukti pembayaran terlebih dahulu.',
            'bukti_bayar.mimes'             => 'Bukti pembayaran harus berformat JPG, PNG, atau PDF.',
            'bukti_bayar.max'               => 'Ukuran bukti pembayaran maksimal 2 MB.',
        ]);

        $metode = MetodePembayaran::aktif()->find($validated['metode_pembayaran_id']);

        if (! $metode) {
            return back()->withInput()->withErrors([
                'metode_pembayaran_id' => 'Metode pembayaran yang dipilih tidak tersedia.',
            ]);
        }

        // Promo dihitung ulang di server (bukan dari angka di form).
        // promoTersedia() hanya berisi promo yang masih berlaku hari ini dan cocok untuk
        // perawatan di booking ini (termasuk promo "semua perawatan"), jadi promo yang
        // baru saja kedaluwarsa atau tidak cocok otomatis ditolak di bawah.
        $totalAsli = $this->totalPerawatan($booking);
        $promo     = null;
        $diskon    = 0;

        if (! empty($validated['promo_id'])) {
            $promo = $this->promoTersedia($booking, $totalAsli)
                ->firstWhere('id', (int) $validated['promo_id']);

            if (! $promo) {
                return back()->withInput()->withErrors([
                    'promo_id' => 'Promo yang dipilih tidak berlaku untuk booking ini atau sudah berakhir.',
                ]);
            }

            $diskon = (int) $promo->diskon_hitung;
        }

        $path = null;

        try {
            $path = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');

            // Rincian saluran (mis. nama bank) disimpan di catatan karena kolom metode berupa enum
            $catatan = trim('Via ' . $metode->metode . '. ' . ($validated['catatan'] ?? ''));

            $hasil = DB::transaction(function () use ($booking, $metode, $promo, $diskon, $totalAsli, $path, $catatan) {

                // Kunci reservasi dan pembayarannya, lalu cek ulang setelah terkunci
                $reservasi  = Reservasi::whereKey($booking->id)->lockForUpdate()->first();
                $pembayaran = Pembayaran::where('reservasi_id', $booking->id)->lockForUpdate()->first();

                if (! $reservasi
                    || ! $pembayaran
                    || $reservasi->status !== 'Menunggu'
                    || $pembayaran->status === 'Lunas') {
                    return ['diproses' => false];
                }

                $buktiLama = $pembayaran->bukti_bayar;

                // Pakai ->update() pada model (bukan query builder) supaya observer tetap berjalan
                $pembayaran->update([
                    'promo_id'              => $promo?->id,
                    'metode_pembayaran'     => $this->jenisPembayaran($metode),
                    'tanggal_bayar'         => $this->jadwal->sekarang()->toDateString(),
                    'jumlah_sebelum_diskon' => $totalAsli,
                    'jumlah_diskon'         => $diskon,
                    'jumlah'                => $totalAsli - $diskon,
                    'bukti_bayar'           => $path,
                    'catatan'               => $catatan,
                    'status'                => 'Menunggu', // Menunggu verifikasi admin, tidak langsung lunas
                ]);

                // Reservasi tetap berstatus 'Menunggu' hingga diverifikasi oleh admin

                return ['diproses' => true, 'buktiLama' => $buktiLama];
            });

        } catch (\Throwable $e) {
            // Transaksi dibatalkan, jadi file yang baru diunggah tidak boleh tertinggal
            if ($path) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Gagal menyimpan bukti pembayaran: ' . $e->getMessage(), [
                'user_id'      => auth()->id(),
                'reservasi_id' => $booking->id,
                'file'         => $e->getFile(),
                'line'         => $e->getLine(),
            ]);

            return back()->withInput()->with('error', 'Pembayaran belum dapat disimpan. Silakan coba lagi.');
        }

        // Ada permintaan lain yang sudah memproses pembayaran ini lebih dulu
        if (empty($hasil['diproses'])) {
            Storage::disk('public')->delete($path);

            return redirect()->route('booking.detail', $booking->id)
                ->with('error', 'Pembayaran untuk booking ini sudah diproses.');
        }

        if (! empty($hasil['buktiLama'])) {
            Storage::disk('public')->delete($hasil['buktiLama']);
        }

        $pesan = 'Bukti pembayaran berhasil diunggah. Booking ' . $booking->kode_reservasi . ' sedang menunggu verifikasi dari admin.';

        if ($diskon > 0) {
            $pesan .= ' Diskon promo Rp' . number_format($diskon, 0, ',', '.') . ' sudah diterapkan.';
        }

        return redirect()->route('booking.detail', $booking->id)->with('success', $pesan);
    }


    // ==================================================================
    //  PEMBATALAN
    // ==================================================================

    /**
     * PATCH /booking/{booking}/batal
     * Boleh dibatalkan bila jadwal masih lebih dari 3 jam lagi.
     * Kurang dari itu pembatalan lewat sistem ditutup (hubungi salon).
     */
    public function batal(Request $request, Reservasi $booking)
    {
        $this->pastikanMilikSendiri($booking);

        $data = $request->validate([
            'alasan_batal' => ['nullable', 'string', 'max:255'],
        ]);

        return DB::transaction(function () use ($booking, $data) {
            // Kunci baris agar dua permintaan bersamaan tidak saling menimpa
            $b = Reservasi::whereKey($booking->id)->lockForUpdate()->first();

            if (! in_array($b->status, ['Menunggu', 'Dikonfirmasi'], true)) {
                return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
            }

            $sekarang = $this->jadwal->sekarang();
            $jadwal   = $sekarang->copy()
                ->setDateFrom(Carbon::parse($b->tanggal))
                ->setTimeFromTimeString((string) $b->jam_mulai);

            if ($sekarang->diffInMinutes($jadwal, false) <= self::BATAS_BATAL_JAM * 60) {
                return back()->with(
                    'error',
                    'Pembatalan lewat sistem sudah ditutup (kurang dari ' . self::BATAS_BATAL_JAM
                    . ' jam sebelum jadwal). Silakan hubungi salon.'
                );
            }

            $b->update([
                'status'          => 'Dibatalkan',
                'alasan_batal'    => $data['alasan_batal'] ?? null,
                'dibatalkan_pada' => $sekarang,
            ]);

            return redirect()->route('booking.detail', $b->id)
                ->with('success', 'Booking ' . $b->kode_reservasi . ' berhasil dibatalkan.');
        });
    }


    // ==================================================================
    //  BUAT BOOKING
    // ==================================================================

    /** GET /booking/buat : terbuka untuk tamu maupun pelanggan login */
    public function create(Request $request)
    {
        $pelanggan = auth()->check() ? $this->pelangganUntukBooking() : null;

        if (auth()->check() && ! $pelanggan) {
            return redirect()->route('booking.daftar')
                ->with('error', 'Akun Anda belum terhubung dengan data pelanggan. Silakan hubungi admin salon.');
        }

        $perawatan = Perawatan::with('kategori')->orderBy('nama')->get();

        // Perawatan yang dipilih dari halaman detail layanan (diabaikan bila ID tidak ada)
        $perawatanDipilih = $perawatan->contains('id', (int) $request->query('perawatan'))
            ? (int) $request->query('perawatan')
            : null;

        return view('pages.booking.create', [
            'pelangganLogin'   => $pelanggan,   // null untuk tamu
            'perawatan'        => $perawatan,
            'perawatanDipilih' => $perawatanDipilih,
            'pegawai'          => Pegawai::where('status', 'Aktif')->orderBy('nama')->get(),
        ]);
    }

    /** GET /booking/cek-jadwal : pratinjau ketersediaan, dipanggil dari form lewat fetch */
    public function cekJadwal(Request $request)
    {
        $v = $request->validate([
            'tanggal'     => ['required', 'date_format:Y-m-d'],
            'pegawai_id'  => ['required', 'integer', Rule::exists((new Pegawai)->getTable(), 'id')->where('status', 'Aktif')],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
        ]);

        $mulai   = $this->jadwal->keMenit($v['jam_mulai']);
        $selesai = $this->jadwal->keMenit($v['jam_selesai']);

        $pesan = $this->jadwal->validasiWaktu($v['tanggal'], $mulai, $selesai);

        if ($pesan) {
            return response()->json([
                'status'                => 'tidak_valid',
                'message'               => $pesan,
                'wait_minutes'          => 0,
                'reservasi_berbenturan' => null,
                'rekomendasi'           => [],
            ]);
        }

        $hasil = $this->jadwal->cekKetersediaan($v['tanggal'], (int) $v['pegawai_id'], $mulai, $selesai);

        $rekomendasi = $hasil['status'] === 'bentrok'
            ? $this->jadwal->cariSlot($v['tanggal'], (int) $v['pegawai_id'], $selesai - $mulai, $mulai)
            : [];

        $pesanStatus = [
            'tersedia' => 'Jadwal tersedia.',
            'tunggu'   => 'Jadwal masih dapat diterima dengan waktu tunggu sekitar ' . $hasil['wait_minutes'] . ' menit.',
            'bentrok'  => 'Jadwal bertabrakan lebih dari ' . PenjadwalanReservasi::TOLERANSI_BENTROK
                . ' menit dengan booking lain. Silakan pilih jadwal alternatif.',
        ][$hasil['status']];

        return response()->json([
            'status'                => $hasil['status'],
            'message'               => $pesanStatus,
            'wait_minutes'          => $hasil['wait_minutes'],
            'reservasi_berbenturan' => $hasil['reservasi_berbenturan'],
            'rekomendasi'           => $rekomendasi,
        ]);
    }

    /** POST /booking : terbuka untuk tamu (nama + WhatsApp) maupun pelanggan login */
    public function store(Request $request)
    {
        $tamu      = ! auth()->check();
        $pelanggan = $tamu ? null : $this->pelangganUntukBooking();

        if (! $tamu && ! $pelanggan) {
            return redirect()->route('booking.daftar')
                ->with('error', 'Akun Anda belum terhubung dengan data pelanggan. Silakan hubungi admin salon.');
        }

        $rules = [
            'perawatan_ids'   => ['required', 'array', 'min:1'],
            'perawatan_ids.*' => ['nullable', 'integer', 'min:1', 'max:' . PenjadwalanReservasi::MAKS_JUMLAH],
            'pegawai_id'      => ['required', 'integer', Rule::exists((new Pegawai)->getTable(), 'id')->where('status', 'Aktif')],
            'tanggal'         => ['required', 'date_format:Y-m-d'],
            'jam_mulai'       => ['required', 'date_format:H:i'],
            'catatan'         => ['nullable', 'string', 'max:500'],
        ];

        if ($tamu) {
            $rules['nama_tamu']   = ['required', 'string', 'min:3', 'max:100'];
            $rules['no_whatsapp'] = ['required', 'regex:/^(\+?62|0)8[0-9]{7,12}$/'];
            $rules['website']     = ['prohibited'];   // honeypot anti-bot: harus kosong
        }

        $validated = $request->validate($rules, [
            'perawatan_ids.required' => 'Pilih minimal satu perawatan.',
            'perawatan_ids.min'      => 'Pilih minimal satu perawatan.',
            'pegawai_id.required'    => 'Pilih terapis terlebih dahulu.',
            'pegawai_id.exists'      => 'Terapis yang dipilih tidak tersedia.',
            'tanggal.required'       => 'Tanggal booking belum dipilih.',
            'jam_mulai.required'     => 'Jam mulai belum dipilih.',
            'catatan.max'            => 'Catatan maksimal 500 karakter.',
            'nama_tamu.required'     => 'Nama lengkap wajib diisi.',
            'nama_tamu.min'          => 'Nama lengkap minimal 3 karakter.',
            'no_whatsapp.required'   => 'Nomor WhatsApp wajib diisi.',
            'no_whatsapp.regex'      => 'Format nomor WhatsApp tidak valid (contoh: 081234567890).',
            'website.prohibited'     => 'Permintaan tidak dapat diproses.',
        ]);

        // Tamu: cari/buat data pelanggan dari nomor WhatsApp
        // (melempar ValidationException bila nomor milik akun terdaftar)
        if ($tamu) {
            $pelanggan = $this->pelangganTamu($validated['nama_tamu'], $validated['no_whatsapp']);
        }

        // Durasi dan harga SELALU dihitung ulang di server, bukan dari data form.
        $ringkas = $this->jadwal->ringkasPerawatan($validated['perawatan_ids']);

        if (! $ringkas) {
            return back()->withInput()->withErrors([
                'perawatan_ids' => 'Perawatan yang dipilih tidak valid. Muat ulang halaman lalu coba lagi.',
            ]);
        }

        $mulai   = $this->jadwal->keMenit($validated['jam_mulai']);
        $selesai = $mulai + $ringkas['durasi'];

        $pesanWaktu = $this->jadwal->validasiWaktu($validated['tanggal'], $mulai, $selesai);

        if ($pesanWaktu) {
            return back()->withInput()->withErrors(['jam_mulai' => $pesanWaktu]);
        }

        try {
            $hasil = DB::transaction(function () use ($validated, $ringkas, $mulai, $selesai, $pelanggan, $tamu) {

                // Kunci baris terapis supaya dua pelanggan tidak bisa memesan slot yang
                // sama pada saat bersamaan. Pengecekan diulang setelah terkunci.
                Pegawai::whereKey($validated['pegawai_id'])
                    ->where('status', 'Aktif')
                    ->lockForUpdate()
                    ->firstOrFail();

                $cek = $this->jadwal->cekKetersediaan(
                    $validated['tanggal'],
                    (int) $validated['pegawai_id'],
                    $mulai,
                    $selesai
                );

                if ($cek['status'] === 'bentrok') {
                    return ['bentrok' => true];
                }

                $reservasi = Reservasi::create([
                    'kode_reservasi' => $this->buatKode(),
                    'pelanggan_id'   => $pelanggan->id,
                    'perawatan_id'   => $ringkas['ids'][0],   // perawatan utama, sama seperti form admin
                    'pegawai_id'     => $validated['pegawai_id'],
                    'tanggal'        => $validated['tanggal'],
                    'jam_mulai'      => $this->jadwal->keJam($mulai) . ':00',
                    'jam_selesai'    => $this->jadwal->keJam($selesai) . ':00',
                    'status'         => 'Menunggu',
                    'sumber'         => $tamu ? 'tamu' : 'akun',
                    'catatan'        => $validated['catatan'] ?? null,
                ]);

                // Jumlah, harga, dan durasi disimpan sebagai snapshot di tabel pivot
                $reservasi->perawatans()->sync($ringkas['pivot']);

                // Wajib: konfirmasi admin memeriksa baris pembayaran, jadi harus ada
                Pembayaran::create([
                    'reservasi_id'          => $reservasi->id,
                    'tanggal_bayar'         => null,
                    'metode_pembayaran'     => null,
                    'promo_id'              => null,
                    'jumlah_sebelum_diskon' => $ringkas['harga'],
                    'jumlah_diskon'         => 0,
                    'jumlah'                => $ringkas['harga'],
                    'status'                => 'Menunggu',
                    'catatan'               => null,
                ]);

                return ['reservasi' => $reservasi];
            });

        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan booking pelanggan: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'tamu'    => $tamu,
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->withInput()->with(
                'error',
                'Booking belum dapat disimpan. Silakan coba beberapa saat lagi.'
            );
        }

        if (! empty($hasil['bentrok'])) {
            $alternatif = $this->jadwal->cariSlot(
                $validated['tanggal'],
                (int) $validated['pegawai_id'],
                $ringkas['durasi'],
                $mulai
            );

            // 'rekomendasi' = nama session yang dibaca view create.blade.php
            return back()
                ->withInput()
                ->withErrors(['jam_mulai' => 'Jadwal yang dipilih baru saja terisi oleh booking lain. Silakan pilih waktu lain.'])
                ->with('rekomendasi', $alternatif);
        }

        $reservasi = $hasil['reservasi'];

        // Tamu: simpan akses ke booking ini di session
        if ($tamu) {
            $request->session()->push('booking_tamu', $reservasi->id);
        }

        $pesan = 'Booking ' . $reservasi->kode_reservasi . ' berhasil dibuat. Silakan lakukan pembayaran.';

        if ($tamu) {
            $pesan .= ' Simpan kode booking dan nomor WhatsApp Anda untuk melacak booking ini kapan saja.';
        }

        return redirect()->route('booking.detail', $reservasi->id)->with('success', $pesan);
    }


    // ==================================================================
    //  PEMBANTU
    // ==================================================================

    /** Kode unik, contoh: RSV-20261023-0007 */
    private function buatKode(): string
    {
        $prefix = 'RSV-' . $this->jadwal->sekarang()->format('Ymd') . '-';

        do {
            $kode = $prefix . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Reservasi::where('kode_reservasi', $kode)->exists());

        return $kode;
    }

    /** Nomor WhatsApp salon untuk tautan "hubungi salon" (isi di config/salon.php) */
    private function kontakSalon(): ?string
    {
        return config('salon.whatsapp');
    }

    /** 0812..., +62812..., 62812... -> 62812... */
    private function normalisasiHp(string $hp): string
    {
        $hp = preg_replace('/\D+/', '', $hp);

        return str_starts_with($hp, '0') ? '62' . substr($hp, 1) : $hp;
    }

    /** Semua bentuk penulisan nomor yang mungkin tersimpan di tabel pelanggan */
    private function variasiHp(string $hp): array
    {
        $n = $this->normalisasiHp($hp);

        return [$n, '0' . substr($n, 2), '+' . $n];
    }

    /**
     * Cari reservasi berdasarkan kode + nomor WhatsApp pelanggan.
     * Nomor dibandingkan setelah dinormalisasi, jadi 0812..., 62812..., dan +62812...
     * dianggap sama. Mengembalikan null bila kode tidak ada atau nomor tidak cocok.
     */
    private function cariBooking(string $kode, string $hp): ?Reservasi
    {
        $r = Reservasi::with(['pelanggan', 'pegawai', 'perawatans', 'perawatan', 'pembayaran'])
            ->whereRaw('UPPER(kode_reservasi) = ?', [strtoupper(trim($kode))])
            ->first();

        if (! $r || ! $r->pelanggan) {
            return null;
        }

        $nomorTersimpan = (string) $r->pelanggan->{self::KOLOM_HP};

        if ($nomorTersimpan === '' || $this->normalisasiHp($nomorTersimpan) !== $this->normalisasiHp($hp)) {
            return null;
        }

        // Reservasi lama / buatan admin bisa belum punya baris di tabel pivot.
        // Pakai perawatan utama (perawatan_id) supaya tabel dan total tetap tampil.
        if ($r->perawatans->isEmpty() && $r->perawatan) {
            $utama = $r->perawatan;
            $utama->setRelation('pivot', (object) [
                'jumlah' => 1,
                'harga'  => $utama->harga ?? 0,
                'durasi' => $utama->durasi ?? null,
            ]);

            $r->setRelation('perawatans', collect([$utama]));
        }

        return $r;
    }

    /** Catat id reservasi di session tamu supaya halaman detail & pembayaran bisa dibuka */
    private function catatAksesTamu(Request $request, Reservasi $booking): void
    {
        $ids = array_map('intval', (array) $request->session()->get('booking_tamu', []));

        if (! in_array((int) $booking->id, $ids, true)) {
            $request->session()->push('booking_tamu', $booking->id);
        }
    }

    /**
     * Data pelanggan untuk booking tanpa akun. Memakai data yang sudah ada bila nomornya
     * cocok dan belum terhubung ke akun. Nomor milik akun terdaftar ditolak supaya booking
     * tamu tidak menempel ke data (member, riwayat) orang lain.
     */
    private function pelangganTamu(string $nama, string $hp): Pelanggan
    {
        $ada = Pelanggan::whereIn(self::KOLOM_HP, $this->variasiHp($hp))->first();

        if ($ada && $ada->user_id) {
            throw ValidationException::withMessages([
                'no_whatsapp' => 'Nomor ini sudah terdaftar pada sebuah akun. Silakan masuk untuk melanjutkan booking.',
            ]);
        }

        return $ada ?? Pelanggan::create([
            'nama'              => $nama,
            self::KOLOM_HP      => $this->normalisasiHp($hp),
            'member'            => 'Tidak',
            'jumlah_kedatangan' => 0,
            // lengkapi kolom wajib lain di tabel pelanggan Anda (jk, tgl_lahir, email, alamat) bila tidak nullable
        ]);
    }

    /** Total harga semua perawatan (snapshot pivot harga x jumlah), sebelum diskon */
    private function totalPerawatan(Reservasi $booking): int
    {
        $total = (int) ($booking->perawatans ?? collect())->sum(fn ($p) =>
            (int) data_get($p, 'pivot.harga', $p->harga) * (int) data_get($p, 'pivot.jumlah', 1)
        );

        if ($total <= 0 && $booking->pembayaran) {
            $total = (int) ($booking->pembayaran->jumlah_sebelum_diskon ?: $booking->pembayaran->jumlah);
        }

        return $total;
    }


    // ------------------------------------------------------------------
    //  PROMO
    // ------------------------------------------------------------------

    /**
     * Ubah status promo yang sudah lewat tanggal selesai menjadi Tidak Aktif.
     * Lapis tambahan selain scheduler (promo:nonaktifkan-kedaluwarsa); gagal di sini
     * tidak boleh menghentikan halaman pembayaran, karena promoTersedia() tetap
     * memfilter berdasarkan tanggal.
     */
    private function nonaktifkanPromoKedaluwarsa(): void
    {
        try {
            Promo::kedaluwarsa()->update(['status' => 'Tidak Aktif']);
        } catch (\Throwable $e) {
            Log::warning('Gagal menonaktifkan promo kedaluwarsa: ' . $e->getMessage());
        }
    }

    /**
     * Besar diskon sebuah promo untuk booking ini. Aturan sama dengan modul admin:
     * - promo untuk perawatan tertentu -> dasar diskon hanya harga x jumlah perawatan itu
     * - promo untuk semua perawatan    -> dasar diskon = total seluruh perawatan
     * Mengembalikan 0 bila promo tidak berlaku untuk booking ini.
     */
    private function diskonPromo(Promo $promo, Reservasi $booking, int $total): int
    {
        $dasar = $total;

        if (! $promo->berlaku_semua) {
            $p = ($booking->perawatans ?? collect())->firstWhere('id', $promo->perawatan_id);

            $dasar = $p
                ? (int) data_get($p, 'pivot.harga', $p->harga) * (int) data_get($p, 'pivot.jumlah', 1)
                : 0;
        }

        if ($dasar <= 0) {
            return 0;
        }

        $diskon = $promo->jenis === 'Persentase'
            ? ($dasar * min((int) $promo->nilai, 100)) / 100
            : $promo->nilai;

        return (int) round(min($diskon, $dasar));
    }

    /**
     * Promo yang sedang berlaku dan cocok untuk booking ini (tiap item punya ->diskon_hitung).
     * Hasil diurutkan dari potongan terbesar, jadi promo pertama = paling hemat.
     */
    private function promoTersedia(Reservasi $booking, int $total)
    {
        $hariIni = $this->jadwal->sekarang()->toDateString();
        $ids     = ($booking->perawatans ?? collect())->pluck('id')->all();

        return Promo::with('perawatan')
            ->berlaku($hariIni)          // status Aktif + hari ini di antara tanggal mulai & selesai
            ->untukPerawatan($ids)       // perawatan_id NULL (semua perawatan) ATAU ada di booking ini
            ->get()
            ->each(fn ($promo) => $promo->setAttribute('diskon_hitung', $this->diskonPromo($promo, $booking, $total)))
            ->filter(fn ($promo) => $promo->diskon_hitung > 0)
            ->sortBy([
                ['diskon_hitung', 'desc'],
                ['nama', 'asc'],
            ])
            ->values();
    }

    /** Petakan metode dari tabel metode pembayaran ke enum: Cash | Transfer | E-Wallet */
    private function jenisPembayaran($metode): string
    {
        $teks = strtolower(trim((string) $metode->metode));

        if (preg_match('/e-?wallet|dana|ovo|gopay|shopee|linkaja|qris/', $teks)) {
            return 'E-Wallet';
        }

        if (preg_match('/cash|tunai/', $teks)) {
            return 'Cash';
        }

        return 'Transfer';
    }


    // ------------------------------------------------------------------
    //  PELANGGAN & AKSES
    // ------------------------------------------------------------------

    /** Data pelanggan milik akun login tanpa membuat baru (untuk halaman baca). Null untuk tamu. */
    private function pelangganSaya(): ?Pelanggan
    {
        if ($this->pelangganSudahDicari) {
            return $this->pelangganCache;
        }

        $user = auth()->user();

        // Tamu (belum login): tidak ada data pelanggan berbasis akun
        if (! $user) {
            $this->pelangganSudahDicari = true;

            return $this->pelangganCache = null;
        }

        $pelanggan = null;

        // Dukungan lama: kolom pelanggan_id langsung di tabel users
        if (! empty($user->pelanggan_id)) {
            $pelanggan = Pelanggan::find($user->pelanggan_id);
        }

        $pelanggan ??= $this->akun->cari($user);

        $this->pelangganSudahDicari = true;

        return $this->pelangganCache = $pelanggan;
    }

    /**
     * Data pelanggan untuk membuat booking (akun login). Dibuat otomatis bila belum ada.
     * Mengembalikan null (dan mencatat log) bila pembuatannya gagal.
     */
    private function pelangganUntukBooking(): ?Pelanggan
    {
        $ada = $this->pelangganSaya();

        if ($ada) {
            return $ada;
        }

        try {
            $this->pelangganSudahDicari = false;

            return $this->pelangganCache = $this->akun->pastikan(auth()->user());

        } catch (\Throwable $e) {
            Log::error('Gagal menyiapkan data pelanggan untuk booking: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
            ]);

            return null;
        }
    }

    private function pelangganId(): ?int
    {
        return optional($this->pelangganSaya())->id;
    }

    /** Query reservasi milik user login (kosong bila pelanggan tidak ditemukan) */
    private function queryMilikSaya()
    {
        $pid = $this->pelangganId();

        return Reservasi::query()->when(
            $pid,
            fn ($q) => $q->where('pelanggan_id', $pid),
            fn ($q) => $q->whereRaw('1 = 0')
        );
    }

    /** Boleh diakses oleh pemilik akun ATAU tamu yang booking-nya tercatat di session */
    private function pastikanMilikSendiri(Reservasi $booking): void
    {
        $pid       = $this->pelangganId();
        $milikAkun = $pid && (int) $booking->pelanggan_id === (int) $pid;
        $milikTamu = in_array(
            (int) $booking->id,
            array_map('intval', (array) session('booking_tamu', [])),
            true
        );

        abort_unless($milikAkun || $milikTamu, 403);
    }

    /** Samakan nama atribut dengan yang dipakai Blade */
    private function map(Reservasi $r): Reservasi
    {
        $perawatans = $r->perawatans ?? collect();

        $r->kode_booking = $r->kode_reservasi;
        $r->jam          = $r->jam_mulai;

        // Total memakai nominal pembayaran bila ada. Bila belum, hitung dari snapshot
        // harga x jumlah di tabel pivot (sebelumnya jumlah perawatan diabaikan).
        $r->total = optional($r->pembayaran)->jumlah
            ?? $perawatans->sum(fn ($p) =>
                (int) data_get($p, 'pivot.harga', $p->harga) * (int) data_get($p, 'pivot.jumlah', 1)
            );

        // Belum perlu bayar bila pembayaran sudah Lunas, bukti sudah dikirim,
        // atau reservasi sudah dibatalkan/selesai
        $lunas      = optional($r->pembayaran)->status === 'Lunas';
        $sudahKirim = filled(optional($r->pembayaran)->bukti_bayar);

        $r->sudah_kirim_bukti = $sudahKirim && ! $lunas;
        $r->perlu_bayar       = $r->status === 'Menunggu' && ! $lunas && ! $sudahKirim;

        $r->setRelation('perawatan', $perawatans);
        $r->setRelation('terapis', $r->pegawai);

        return $r;
    }
}