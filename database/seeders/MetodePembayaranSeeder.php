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
            ['metode' => 'Transfer BCA', 'tipe' => 'Transfer', 'norek' => '1234567890',   'an' => 'Azzahra Salon & Spa', 'aktif' => true],
            ['metode' => 'Transfer BRI', 'tipe' => 'Transfer', 'norek' => '0987654321',   'an' => 'Azzahra Salon & Spa', 'aktif' => true],
            ['metode' => 'DANA',         'tipe' => 'Transfer', 'norek' => '081234567890', 'an' => 'Azzahra Salon & Spa', 'aktif' => true],
            ['metode' => 'QRIS',         'tipe' => 'QRIS',     'norek' => '-',            'an' => 'Azzahra Salon & Spa', 'aktif' => true],
        ];

        foreach ($data as $item) {
            MetodePembayaran::updateOrCreate(
                ['metode' => $item['metode']],
                $item
            );
        }

        $this->command?->info('Metode pembayaran berhasil dibuat.');
    }
}