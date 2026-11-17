<?php

namespace App\Console\Commands;

use App\Services\LaporanBulananService;
use Illuminate\Console\Command;

class BuatLaporanBulanan extends Command
{
    protected $signature = 'laporan:bulanan
                            {periode? : Format Y-m, default bulan ini}
                            {--lalu : Pakai bulan lalu}
                            {--sejak= : Isi semua bulan yang belum punya laporan sejak Y-m ini}
                            {--final : Tandai laporan sebagai Final}';

    protected $description = 'Buat atau perbarui laporan bulanan secara otomatis';

    public function handle(LaporanBulananService $service): int
    {
        if ($sejak = $this->option('sejak')) {
            $jumlah = $service->sinkronkan($sejak, now()->toDateString());
            $this->info("{$jumlah} laporan bulanan dibuat.");

            return self::SUCCESS;
        }

        $periode = $this->option('lalu')
            ? now()->subMonthNoOverflow()->format('Y-m')
            : ($this->argument('periode') ?? now()->format('Y-m'));

        $status  = $this->option('final') ? 'Final' : 'Draft';
        $laporan = $service->buatAtauPerbarui($periode, $status);

        $this->info("Laporan {$laporan->kode_laporan} ({$periode}) berstatus {$laporan->status}.");

        return self::SUCCESS;
    }
}