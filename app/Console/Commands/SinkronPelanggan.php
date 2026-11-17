<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\PelangganAkun;
use Illuminate\Console\Command;

/**
 * Membuat data Pelanggan untuk semua akun berperan pelanggan yang belum punya.
 *
 * Contoh:
 *   php artisan pelanggan:sinkron --dry-run          lihat dulu tanpa mengubah apa pun
 *   php artisan pelanggan:sinkron                    jalankan
 *   php artisan pelanggan:sinkron --tautkan-email    tautkan juga data lama yang emailnya
 *                                                    sama walau email belum terverifikasi
 *
 * Aman dijalankan berulang kali: akun yang sudah punya data pelanggan dilewati.
 */
class SinkronPelanggan extends Command
{
    protected $signature = 'pelanggan:sinkron
                            {--tautkan-email : Tautkan data pelanggan lama berdasarkan email tanpa syarat verifikasi}
                            {--dry-run : Hanya tampilkan hasilnya, tanpa menyimpan}';

    protected $description = 'Daftarkan semua akun pelanggan yang belum punya data pelanggan';

    public function handle(PelangganAkun $akun): int
    {
        $tautkanEmail = (bool) $this->option('tautkan-email');
        $dryRun       = (bool) $this->option('dry-run');

        $sudahAda = $dibuat = $ditautkan = $gagal = 0;

        if ($dryRun) {
            $this->warn('Mode dry-run: tidak ada data yang disimpan.');
        }

        User::query()->orderBy('id')->chunkById(200, function ($users) use (
            $akun, $tautkanEmail, $dryRun, &$sudahAda, &$dibuat, &$ditautkan, &$gagal
        ) {
            foreach ($users as $user) {

                if (!$user->isPelanggan()) {
                    continue;
                }

                try {
                    if ($akun->cari($user)) {
                        $sudahAda++;
                        continue;
                    }

                    if ($dryRun) {
                        $akun->cariDataLama($user, $tautkanEmail) ? $ditautkan++ : $dibuat++;
                        continue;
                    }

                    $pelanggan = $akun->pastikan($user, $tautkanEmail);

                    $pelanggan->wasRecentlyCreated ? $dibuat++ : $ditautkan++;

                } catch (\Throwable $e) {
                    $gagal++;

                    $this->error("Akun #{$user->id} ({$user->email}) gagal: {$e->getMessage()}");
                }
            }
        });

        $this->newLine();
        $this->table(
            ['Hasil', 'Jumlah'],
            [
                ['Sudah punya data pelanggan', $sudahAda],
                [$dryRun ? 'Akan dibuat baru' : 'Dibuat baru', $dibuat],
                [$dryRun ? 'Akan ditautkan ke data lama' : 'Ditautkan ke data lama', $ditautkan],
                ['Gagal', $gagal],
            ]
        );

        return $gagal > 0 ? self::FAILURE : self::SUCCESS;
    }
}