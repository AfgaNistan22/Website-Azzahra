<?php

namespace App\Console\Commands;

use App\Services\LaporanMasterService;
use Illuminate\Console\Command;

class BuatLaporanMaster extends Command
{
    protected $signature = 'laporan:master
                            {kategori? : Kunci kategori (mis. pelanggan). Kosong = semua kategori}
                            {--final : Tandai laporan sebagai Final}';

    protected $description = 'Buat dan perbarui laporan master secara otomatis';

    public function handle(LaporanMasterService $service): int
    {
        if ($kunci = $this->argument('kategori')) {
            if (!$service->kategori($kunci)) {
                $this->error("Kategori '{$kunci}' tidak ditemukan.");

                return self::FAILURE;
            }

            $laporan = $service->buatAtauPerbarui($kunci, null, null, $this->option('final') ? 'Final' : 'Draft');

            $this->info("Laporan {$laporan->kode_laporan} ({$kunci}) berstatus {$laporan->status}.");

            return self::SUCCESS;
        }

        $dibuat     = $service->sinkronkan();
        $diperbarui = $service->segarkanDraft();

        $this->info("{$dibuat} laporan dibuat, {$diperbarui} laporan Draft diperbarui.");

        return self::SUCCESS;
    }
}