<?php

namespace App\Console\Commands;

use App\Models\Reservasi;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BatalkanBookingKedaluwarsa extends Command
{
    protected $signature = 'booking:batalkan-kedaluwarsa {--jam=2 : Batas toleransi jam sebelum dibatalkan otomatis}';

    protected $description = 'Membatalkan reservasi yang belum dibayar setelah melewati batas waktu toleransi';

    public function handle(): int
    {
        $jam = max(1, (int) $this->option('jam'));
        $batasWaktu = Carbon::now()->subHours($jam);

        $this->info("Memeriksa booking menunggu yang dibuat sebelum {$batasWaktu->toDateTimeString()}...");

        try {
            // Ambil reservasi berstatus Menunggu yang pembayarannya belum lunas dan belum ada bukti bayar
            $reservasiKedaluwarsa = Reservasi::where('status', 'Menunggu')
                ->where('created_at', '<=', $batasWaktu)
                ->whereHas('pembayaran', function ($q) {
                    $q->where('status', 'Menunggu')
                      ->whereNull('bukti_bayar');
                })
                ->with('pembayaran')
                ->get();

            $total = 0;

            foreach ($reservasiKedaluwarsa as $reservasi) {
                DB::transaction(function () use ($reservasi, $jam) {
                    $alasan = "Dibatalkan otomatis oleh sistem (tidak ada bukti pembayaran dalam {$jam} jam).";

                    $reservasi->update([
                        'status'          => 'Dibatalkan',
                        'alasan_batal'    => $alasan,
                        'dibatalkan_pada' => now(),
                    ]);

                    if ($reservasi->pembayaran) {
                        $reservasi->pembayaran->update([
                            'status'  => 'Dibatalkan',
                            'catatan' => $alasan,
                        ]);
                    }
                });

                $total++;
            }

            $this->info("Berhasil membatalkan {$total} reservasi kedaluwarsa.");

            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('Gagal membatalkan reservasi kedaluwarsa: ' . $e->getMessage());

            Log::error('BatalkanBookingKedaluwarsa Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return self::FAILURE;
        }
    }
}
