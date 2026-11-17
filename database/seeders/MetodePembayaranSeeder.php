<?php

namespace Database\Seeders;

use App\Models\MetodePembayaran;
use Illuminate\Database\Seeder;

/**
 * Mengisi data metode pembayaran awal (tabel metode_pembayarans).
 * GANTI norek dan an dengan data asli salon.
 */
class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['metode' => 'Transfer BCA', 'norek' => '1234567890',   'an' => 'Azzahra Salon & Spa'],
            ['metode' => 'Transfer BRI', 'norek' => '0987654321',   'an' => 'Azzahra Salon & Spa'],
            ['metode' => 'DANA',         'norek' => '081234567890', 'an' => 'Azzahra Salon & Spa'],
        ];

        foreach ($data as $item) {
            MetodePembayaran::updateOrCreate(
                ['metode' => $item['metode']],
                ['norek' => $item['norek'], 'an' => $item['an'], 'aktif' => true]
            );
        }

        $this->command?->info('Metode pembayaran berhasil dibuat.');
    }
}