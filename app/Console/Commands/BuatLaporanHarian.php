<?php

namespace App\Console\Commands;

use App\Services\LaporanHarianService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class BuatLaporanHarian extends Command
{
    protected $signature = 'laporan:harian
                            {tanggal? : Format Y-m-d, default hari ini}
                            {--sejak= : Isi semua tanggal yang belum punya laporan sejak tanggal ini}
                            {--final : Tandai laporan sebagai Final}';

    protected $description = 'Buat atau perbarui laporan harian secara otomatis';

    public function handle(LaporanHarianService $service): int
    {
        if ($sejak = $this->option('sejak')) {
            $jumlah = $service->sinkronkan($sejak, now()->toDateString());
            $this->info("{$jumlah} laporan harian dibuat.");

            return self::SUCCESS;
        }

        $tanggal = Carbon::parse($this->argument('tanggal') ?? now())->toDateString();
        $status  = $this->option('final') ? 'Final' : 'Draft';

        $laporan = $service->buatAtauPerbarui($tanggal, $status);

        $this->info("Laporan {$laporan->kode_laporan} ({$tanggal}) berstatus {$laporan->status}.");

        return self::SUCCESS;
    }
}