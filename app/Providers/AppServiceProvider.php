<?php

namespace App\Providers;

use App\Models\KategoriPerawatan;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use App\Observers\PembayaranObserver;
use App\Observers\ReservasiObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Bootstrap Pagination
        |--------------------------------------------------------------------------
        */

        Paginator::useBootstrapFive();

        /*
        |--------------------------------------------------------------------------
        | Reservasi Observer
        |--------------------------------------------------------------------------
        */

        Reservasi::observe(ReservasiObserver::class);
        Pembayaran::observe(PembayaranObserver::class);

        /*
        |--------------------------------------------------------------------------
        | Pembatas permintaan untuk booking (tamu dan pelanggan login)
        | Hitungan tiap pembatas terpisah supaya cek jadwal yang sering dipanggil
        | tidak menghabiskan jatah tombol "Buat Booking".
        |--------------------------------------------------------------------------
        */

        // cek-jadwal, halaman detail, pembayaran, pembatalan
        RateLimiter::for('booking-umum', function (Request $request) {
            return Limit::perMinute(60)->by('umum|' . $request->ip());
        });

        // simpan booking baru
        RateLimiter::for('booking-simpan', function (Request $request) {
            return Limit::perMinute(6)->by('simpan|' . $request->ip());
        });

        // lacak booking (kode + nomor WhatsApp): dibatasi ketat agar tidak bisa ditebak
        RateLimiter::for('booking-lacak', function (Request $request) {
            return Limit::perMinute(5)->by('lacak|' . $request->ip());
        });

        /*
        |--------------------------------------------------------------------------
        | Kategori perawatan untuk navbar guest
        |--------------------------------------------------------------------------
        */

        View::composer('pages.auth.guest', function ($view) {
            $view->with(
                'navCategories',
                KategoriPerawatan::where('status', 'Aktif')
                    ->orderBy('nama')
                    ->get()
            );
        });
    }
}