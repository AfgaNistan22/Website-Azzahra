<?php

namespace Tests\Unit;

use App\Services\PenjadwalanReservasi;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PenjadwalanReservasiTest extends TestCase
{
    private PenjadwalanReservasi $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PenjadwalanReservasi();
    }

    /**
     * Uji konversi string jam ke menit sejak tengah malam.
     */
    public function test_konversi_jam_ke_menit(): void
    {
        $this->assertEquals(540, $this->service->keMenit('09:00'));
        $this->assertEquals(630, $this->service->keMenit('10:30'));
        $this->assertEquals(1020, $this->service->keMenit('17:00'));
    }

    /**
     * Uji konversi menit integer kembali ke format string jam "HH:MM".
     */
    public function test_konversi_menit_ke_jam(): void
    {
        $this->assertEquals('09:00', $this->service->keJam(540));
        $this->assertEquals('10:30', $this->service->keJam(630));
        $this->assertEquals('17:00', $this->service->keJam(1020));
    }

    /**
     * Uji validasi jam operasional salon (09:00 - 17:00).
     */
    public function test_validasi_jam_di_luar_operasional(): void
    {
        $besok = Carbon::tomorrow(PenjadwalanReservasi::ZONA_WAKTU)->toDateString();

        // Terlalu pagi (< 09:00)
        $mulaiPagi = $this->service->keMenit('08:30');
        $selesaiPagi = $this->service->keMenit('09:30');
        $errorPagi = $this->service->validasiWaktu($besok, $mulaiPagi, $selesaiPagi);
        $this->assertNotNull($errorPagi);
        $this->assertStringContainsString('09:00', $errorPagi);

        // Melewati jam tutup (> 17:00)
        $mulaiSore = $this->service->keMenit('16:30');
        $selesaiSore = $this->service->keMenit('17:30');
        $errorSore = $this->service->validasiWaktu($besok, $mulaiSore, $selesaiSore);
        $this->assertNotNull($errorSore);
        $this->assertStringContainsString('17:00', $errorSore);

        // Jam selesai lebih kecil dari jam mulai
        $errorTerbalik = $this->service->validasiWaktu($besok, 600, 500);
        $this->assertNotNull($errorTerbalik);

        // Jadwal valid di hari esok
        $validMulai = $this->service->keMenit('10:00');
        $validSelesai = $this->service->keMenit('11:30');
        $this->assertNull($this->service->validasiWaktu($besok, $validMulai, $validSelesai));
    }

    /**
     * Uji validasi tanggal di masa lalu.
     */
    public function test_validasi_tanggal_di_masa_lalu(): void
    {
        $kemarin = Carbon::yesterday(PenjadwalanReservasi::ZONA_WAKTU)->toDateString();
        $mulai = $this->service->keMenit('10:00');
        $selesai = $this->service->keMenit('11:00');

        $error = $this->service->validasiWaktu($kemarin, $mulai, $selesai);
        $this->assertNotNull($error);
        $this->assertStringContainsString('sebelum hari ini', $error);
    }
}
