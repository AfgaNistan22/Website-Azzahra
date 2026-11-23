<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\GaleriAdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalTerapisController;
use App\Http\Controllers\KategoriPerawatanController;
use App\Http\Controllers\LaporanBulananController;
use App\Http\Controllers\LaporanHarianController;
use App\Http\Controllers\LaporanMasterController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\PerawatanLayananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\RiwayatReservasiController;
use App\Http\Controllers\HalamanPublikController;
use App\Http\Controllers\TestimoniController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

// ==================================================
// PEMBATAS PERMINTAAN BOOKING
// Cadangan agar route tetap jalan walau AppServiceProvider belum termuat.
// Bila limiter yang sama sudah didaftarkan di AppServiceProvider, blok ini
// cukup dihapus. Jangan pakai php artisan route:cache selama blok ini ada,
// karena kode di file route tidak dijalankan saat route di-cache.
// ==================================================

RateLimiter::for('booking-umum', fn (Request $request) => Limit::perMinute(60)->by('umum|' . $request->ip()));
RateLimiter::for('booking-simpan', fn (Request $request) => Limit::perMinute(6)->by('simpan|' . $request->ip()));
RateLimiter::for('booking-lacak', fn (Request $request) => Limit::perMinute(5)->by('lacak|' . $request->ip()));


// ==================================================
// AUTH
// Tidak memerlukan login
// ==================================================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Alias saja: nama 'home' sudah dipakai oleh route '/' di atas.
// Nama yang sama dua kali membuat route:cache gagal.
Route::get('/home', [HomeController::class, 'index']);

Route::get('/layanan/{id}', [HomeController::class, 'detail'])
    ->name('layanan.detail');

Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

// Login / daftar dengan Google dan Facebook (hanya untuk tamu)
Route::middleware('guest')->group(function () {

    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->whereIn('provider', ['google', 'facebook'])
        ->middleware('throttle:20,1')
        ->name('auth.social.redirect');

    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->whereIn('provider', ['google', 'facebook'])
        ->middleware('throttle:20,1')
        ->name('auth.social.callback');
});

Route::get('/jadwal-terapis', [JadwalTerapisController::class, 'index'])
    ->name('jadwal-terapis.index');

Route::get('/perawatan-layanan', [PerawatanLayananController::class, 'index'])
    ->name('perawatan-layanan.index');

Route::get('/lokasi', [LokasiController::class, 'index'])
    ->name('lokasi.index');

Route::get('/promo-terbaru', [HalamanPublikController::class, 'promo'])->name('promo.publik');
Route::get('/sejarah', [HalamanPublikController::class, 'sejarah'])->name('sejarah');
Route::get('/kontak-kami', [HalamanPublikController::class, 'kontak'])->name('kontak');
Route::get('/galeri', [HalamanPublikController::class, 'galeri'])->name('galeri.index');
// bagian publik (tanpa login), dekat route /galeri
Route::get('/testimoni', [TestimoniController::class, 'index'])->name('testimoni.index');


// ==================================================
// BOOKING (TANPA LOGIN)
// Dapat diakses tamu maupun pelanggan yang sudah login.
// Hak akses tiap booking diperiksa di BookingController
// (pastikanMilikSendiri: pemilik akun ATAU tamu dengan session booking_tamu).
//
// Urutan penting: /booking/buat, /booking/cek-jadwal, dan /booking/lacak
// harus berada di atas /booking/{booking}.
//
// Limiter bernama (booking-umum, booking-simpan, booking-lacak) didaftarkan
// di App\Providers\AppServiceProvider::boot(). Tiap limiter punya penghitung
// sendiri, jadi seringnya cek jadwal tidak menghabiskan jatah kirim booking.
// ==================================================

Route::middleware('throttle:booking-umum')->group(function () {

    Route::get('/booking/buat', [BookingController::class, 'create'])
        ->name('booking.create');

    Route::get('/booking/cek-jadwal', [BookingController::class, 'cekJadwal'])
        ->name('booking.cek-jadwal');

    Route::post('/booking', [BookingController::class, 'store'])
        ->middleware('throttle:booking-simpan')
        ->name('booking.store');

    // Lacak booking (kode booking + nomor WhatsApp)
    Route::get('/booking/lacak', [BookingController::class, 'lacakForm'])
        ->name('booking.lacak');

    Route::post('/booking/lacak', [BookingController::class, 'lacak'])
        ->middleware('throttle:booking-lacak')
        ->name('booking.lacak.cari');

    Route::get('/booking/{booking}', [BookingController::class, 'detail'])
        ->whereNumber('booking')
        ->name('booking.detail');

    Route::get('/booking/{booking}/pembayaran', [BookingController::class, 'pembayaran'])
        ->whereNumber('booking')
        ->name('booking.pembayaran');

    Route::post('/booking/{booking}/pembayaran', [BookingController::class, 'simpanPembayaran'])
        ->whereNumber('booking')
        ->middleware('throttle:booking-simpan')
        ->name('booking.pembayaran.simpan');

    // Pembatalan: boleh bila jadwal > 3 jam lagi (aturan dicek ulang di controller)
    Route::patch('/booking/{booking}/batal', [BookingController::class, 'batal'])
        ->whereNumber('booking')
        ->name('booking.batal');
});


// ==================================================
// SEMUA USER YANG SUDAH LOGIN
// Admin dan pelanggan
// ==================================================

Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::match(['post', 'put'], '/profile/{id}', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::get('/admin/profile', [ProfileController::class, 'index'])
        ->middleware(['auth', 'admin'])
        ->name('profile.admin');

    // Ganti password
    Route::get('/change', [AuthController::class, 'change_pass'])
        ->name('change.password');

    Route::post('/change/{id}', [
        AuthController::class,
        'change_password'
    ])->name('change.password.update');

    // Testimoni: kirim & hapus hanya untuk akun yang login
    // (sebelumnya berada di luar grup auth walau komentarnya menyebut grup auth).
    Route::post('/testimoni', [TestimoniController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('testimoni.store');

    Route::delete('/testimoni/{testimoni}', [TestimoniController::class, 'destroy'])
        ->whereNumber('testimoni')
        ->name('testimoni.destroy');

    // Khusus akun login: booking terdekat dan seluruh riwayat booking
    Route::get('/booking-saya',   [BookingController::class, 'saya'])->name('booking.saya');
    Route::get('/daftar-booking', [BookingController::class, 'daftar'])->name('booking.daftar');

});


// ==================================================
// KHUSUS ADMIN
// Wajib login dan role_id = 1
// ==================================================

Route::middleware(['auth', 'admin'])->group(function () {

    // ---------------- DASHBOARD ----------------

    Route::get('/dashboard', [
        DashboardController::class,
        'dash'
    ])->name('dashboard');

    Route::get('/dashboard/live', [
        DashboardController::class,
        'liveData'
    ])->name('dashboard.live');

    Route::get('/dashboard/{kode_cbg}', [
        DashboardController::class,
        'dash'
    ])->name('dashboard.cabang');


    // ---------------- LAPORAN PDF ----------------

    Route::get('/pelanggan/laporan/pdf', [
        PelangganController::class,
        'laporanPdf'
    ])->name('pelanggan.laporan.pdf');

    Route::get('/reservasi/laporan/pdf', [
        ReservasiController::class,
        'laporanPdf'
    ])->name('reservasi.laporan.pdf');

    Route::get('/perawatan/laporan/pdf', [
        PerawatanController::class,
        'laporanPdf'
    ])->name('perawatan.laporan.pdf');

    Route::get('/pegawai/laporan/pdf', [
        PegawaiController::class,
        'laporanPdf'
    ])->name('pegawai.laporan.pdf');

    Route::get('/cabang/laporan/pdf', [
        CabangController::class,
        'laporanPdf'
    ])->name('cabang.laporan.pdf');

    Route::get('/promo/laporan/pdf', [
        PromoController::class,
        'laporanPdf'
    ])->name('promo.laporan.pdf');

    Route::get('/pembayaran/laporan/pdf', [
        PembayaranController::class,
        'laporanPdf'
    ])->name('pembayaran.laporan.pdf');

    Route::get('/kategori/laporan/pdf', [
        KategoriPerawatanController::class,
        'laporanPdf'
    ])->name('kategori.laporan.pdf');

    Route::get('/gaji/laporan/pdf', [
        GajiController::class,
        'laporanPdf'
    ])->name('gaji.laporan.pdf');

    Route::get('/pajak/laporan/pdf', [
        PajakController::class,
        'laporanPdf'
    ])->name('pajak.laporan.pdf');

    Route::get('/pemasukan/laporan/pdf', [
        PemasukanController::class,
        'laporanPdf'
    ])->name('pemasukan.laporan.pdf');

    Route::get('/pengeluaran/laporan/pdf', [
        PengeluaranController::class,
        'laporanPdf'
    ])->name('pengeluaran.laporan.pdf');

    Route::get('/harian/laporan/pdf', [
        LaporanHarianController::class,
        'laporanPdf'
    ])->name('laporan_harian.laporan.pdf');

    Route::get('/laporan-bulanan/pdf', [
        LaporanBulananController::class,
        'laporanPdf'
    ])->name('laporan_bulanan.laporan.pdf');

    Route::get('/laporan-master/pdf', [
        LaporanMasterController::class,
        'laporanPdf'
    ])->name('laporan_master.laporan.pdf');


    // ---------------- PREVIEW LAPORAN ----------------

    Route::get('/harian/preview', [
        LaporanHarianController::class,
        'preview'
    ])->name('laporan_harian.preview');

    Route::get('/laporan-bulanan/preview', [
        LaporanBulananController::class,
        'preview'
    ])->name('laporan_bulanan.preview');

    Route::get('/laporan-master/preview', [
        LaporanMasterController::class,
        'preview'
    ])->name('laporan_master.preview');


    // ---------------- PENGELOLAAN RESERVASI ----------------

    Route::get('/reservasi/cek-jadwal', [
        ReservasiController::class,
        'cekJadwal'
    ])->name('reservasi.cekJadwal');

    Route::patch('/reservasi/{reservasi}/konfirmasi', [
        ReservasiController::class,
        'konfirmasi'
    ])->name('reservasi.konfirmasi');

    Route::patch('/reservasi/{reservasi}/selesai', [
        ReservasiController::class,
        'selesai'
    ])->name('reservasi.selesai');

    Route::patch('/reservasi/{reservasi}/batal', [
        ReservasiController::class,
        'batal'
    ])->name('reservasi.batal');


    // ---------------- RESOURCE ADMIN ----------------

    Route::resource('reservasi', ReservasiController::class);

    Route::resource('gaji', GajiController::class);

    Route::resource('pajak', PajakController::class);

    Route::resource('pemasukan', PemasukanController::class);

    Route::resource('kategori', KategoriPerawatanController::class);

    Route::resource('pelanggan', PelangganController::class);

    Route::resource('perawatan', PerawatanController::class);

    Route::resource(
        'riwayat-reservasi',
        RiwayatReservasiController::class
    );

    Route::resource(
        'laporan/harian',
        LaporanHarianController::class
    )->parameters([
        'harian' => 'laporan_harian'
    ]);

    Route::resource(
        'laporan/bulanan',
        LaporanBulananController::class
    )->parameters([
        'bulanan' => 'laporan_bulanan'
    ]);

    Route::resource(
        'laporan/master',
        LaporanMasterController::class
    )->parameters([
        'master' => 'laporan_master'
    ]);

    Route::resource('pembayaran', PembayaranController::class);

    Route::resource('pengeluaran', PengeluaranController::class);

    Route::resource('pegawai', PegawaiController::class);

    Route::resource('history', HistoryController::class);

    Route::resource('cabang', CabangController::class);

    Route::resource('promo', PromoController::class);

    // Pengelolaan galeri foto (unggah, ubah, hapus satu per satu atau massal)
    Route::match(['post', 'delete'], '/galeri-admin/hapus-massal', [GaleriAdminController::class, 'hapusMassal'])
        ->name('galeri-admin.hapus-massal');

    Route::resource('galeri-admin', GaleriAdminController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['galeri-admin' => 'galeri'])
        ->names('galeri-admin');

});


// ==================================================
// KHUSUS PELANGGAN
// Wajib login dan role_id = 2
// ==================================================

Route::middleware(['auth', 'pelanggan'])->group(function () {

    // Untuk sementara pelanggan hanya dapat mengakses
    // profil dan fitur umum yang sudah tersedia.
    //
    // Jangan tambahkan Route::resource('reservasi', ...)
    // sebelum akses reservasi pelanggan dibatasi berdasarkan
    // pemilik reservasi di controller.

});