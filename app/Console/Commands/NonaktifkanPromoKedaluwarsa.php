<?php

namespace App\Console\Commands;

use App\Models\Promo;
use Illuminate\Console\Command;

class NonaktifkanPromoKedaluwarsa extends Command
{
    protected $signature   = 'promo:nonaktifkan-kedaluwarsa';
    protected $description = 'Ubah status promo menjadi Tidak Aktif jika sudah melewati tanggal selesai';

    public function handle(): int
    {
        $jumlah = Promo::kedaluwarsa()->update(['status' => 'Tidak Aktif']);

        $this->info("{$jumlah} promo dinonaktifkan.");

        return self::SUCCESS;
    }
}