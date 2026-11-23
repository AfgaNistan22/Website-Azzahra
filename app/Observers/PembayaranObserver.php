<?php

namespace App\Observers;

use App\Models\Pembayaran;
use App\Models\Pemasukan;
use Illuminate\Support\Facades\Log;
use Throwable;

class PembayaranObserver
{
    /**
     * Dijalankan ketika pembayaran selesai dibuat atau diperbarui.
     * Menjaga tabel pemasukan selalu sinkron dengan status pembayaran secara otomatis.
     */
    public function saved(Pembayaran $pembayaran): void
    {
        try {
            if ($pembayaran->status === 'Lunas') {
                Pemasukan::syncFromPembayaran($pembayaran);
            } else {
                Pemasukan::cancelFromPembayaran($pembayaran);
            }
        } catch (Throwable $e) {
            Log::error('PembayaranObserver saved error: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Dijalankan ketika pembayaran dihapus.
     */
    public function deleted(Pembayaran $pembayaran): void
    {
        try {
            Pemasukan::cancelFromPembayaran($pembayaran);
        } catch (Throwable $e) {
            Log::error('PembayaranObserver deleted error: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id,
            ]);
        }
    }
}
