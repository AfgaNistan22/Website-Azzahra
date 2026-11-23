<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('laporan:harian')
    ->dailyAt('23:55')
    ->withoutOverlapping();

// Tiap malam: update angka bulan berjalan (Draft)
Schedule::command('laporan:bulanan')
    ->dailyAt('23:58')
    ->withoutOverlapping();

// Tanggal 1 dini hari: kunci laporan bulan lalu jadi Final
Schedule::command('laporan:bulanan --lalu --final')
    ->monthlyOn(1, '00:30')
    ->withoutOverlapping();

// Setiap hari: nonaktifkan promo yang sudah lewat tanggal selesai
Schedule::command('promo:nonaktifkan-kedaluwarsa')
    ->dailyAt('00:01')
    ->withoutOverlapping();

// Setiap 30 menit: batalkan booking yang tidak dibayar dalam batas toleransi (2 jam)
Schedule::command('booking:batalkan-kedaluwarsa')
    ->everyThirtyMinutes()
    ->withoutOverlapping();