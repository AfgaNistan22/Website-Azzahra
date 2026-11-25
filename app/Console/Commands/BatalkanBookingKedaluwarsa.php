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
    protected $signature = 'booking:batalkan-kedaluwarsa {--jam=2 : Batas toleransi jam tanpa pembayaran}';

    protected $description = 'Membatalkan reservasi berstatus Menunggu yang telah melewati tanggal reservasi atau belum dibayar';

    public function handle(): int
    {
        $jam = max(1, (int) $this->option('jam'));

        $this->info("Memeriksa reservasi berstatus Menunggu yang kedaluwarsa...");

        try {
            $total = self::batalkanOtomatis($jam);

            $this->info("Selesai: {$total} reservasi berhasil dibatalkan otomatis.");

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

    /**
     * Membatalkan seluruh reservasi kedaluwarsa:
     * 1. Melewati tanggal reservasi (tanggal < hari ini ATAU tanggal == hari ini dan jam_mulai sudah lewat).
     * 2. Melewati batas waktu toleransi pembayaran (default 2 jam tanpa bukti bayar).
     *
     * @return int Jumlah reservasi yang dibatalkan
     */
    public static function batalkanOtomatis(int $jam = 2): int
    {
        $now        = Carbon::now('Asia/Jakarta');
        $today      = $now->toDateString();
        $currentTime = $now->format('H:i:s');
        $batasWaktu = $now->copy()->subHours($jam);

        $total = 0;

        // Skenario 1: Reservasi Menunggu yang jadwalnya sudah lewat dari sekarang
        $lewatJadwal = Reservasi::where('status', 'Menunggu')
            ->where(function ($q) use ($today, $currentTime) {
                $q->whereDate('tanggal', '<', $today)
                  ->orWhere(function ($q2) use ($today, $currentTime) {
                      $q2->whereDate('tanggal', '=', $today)
                         ->where('jam_mulai', '<=', $currentTime);
                  });
            })
            ->with('pembayaran')
            ->get();

        foreach ($lewatJadwal as $res) {
            DB::transaction(function () use ($res) {
                $alasan = 'Dibatalkan otomatis oleh sistem: Melewati jadwal reservasi yang dipesan.';

                $res->update([
                    'status'          => 'Dibatalkan',
                    'alasan_batal'    => $alasan,
                    'dibatalkan_pada' => now(),
                ]);

                if ($res->pembayaran && $res->pembayaran->status !== 'Lunas') {
                    $res->pembayaran->update([
                        'status'  => 'Dibatalkan',
                        'catatan' => $alasan,
                    ]);
                }
            });

            $total++;
        }

        // Skenario 2: Reservasi Menunggu yang belum ada bukti bayar dan sudah lewat batas jam
        $tanpaBukti = Reservasi::where('status', 'Menunggu')
            ->where('created_at', '<=', $batasWaktu)
            ->whereHas('pembayaran', function ($q) {
                $q->where('status', 'Menunggu')
                  ->whereNull('bukti_bayar');
            })
            ->with('pembayaran')
            ->get();

        foreach ($tanpaBukti as $res) {
            DB::transaction(function () use ($res, $jam) {
                $alasan = "Dibatalkan otomatis oleh sistem: Bukti pembayaran tidak diunggah dalam batas {$jam} jam.";

                $res->update([
                    'status'          => 'Dibatalkan',
                    'alasan_batal'    => $alasan,
                    'dibatalkan_pada' => now(),
                ]);

                if ($res->pembayaran && $res->pembayaran->status !== 'Lunas') {
                    $res->pembayaran->update([
                        'status'  => 'Dibatalkan',
                        'catatan' => $alasan,
                    ]);
                }
            });

            $total++;
        }

        return $total;
    }
}
