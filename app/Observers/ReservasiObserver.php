<?php

namespace App\Observers;

use App\Models\Reservasi;
use App\Models\RiwayatReservasi;

class ReservasiObserver
{
    /**
     * Ketika reservasi selesai di-update.
     */
    public function updated(Reservasi $reservasi): void
    {
        $status = $reservasi->status;

        // Hanya status berikut yang masuk riwayat
        if (!in_array($status, ['Selesai', 'Dibatalkan'])) {
            return;
        }

        // Pastikan relasi tersedia
        $reservasi->load([
            'pelanggan',
            'perawatan',
            'pegawai'
        ]);

        RiwayatReservasi::updateOrCreate(

            [
                'reservasi_id' => $reservasi->id
            ],

            [
                'pelanggan_id' => $reservasi->pelanggan_id,

                'perawatan_id' => $reservasi->perawatan_id,

                'pegawai_id' => $reservasi->pegawai_id,

                'nama_pelanggan' =>
                    $reservasi->pelanggan->nama ?? '-',

                'nama_perawatan' =>
                    $reservasi->perawatan->nama ?? '-',

                'nama_pegawai' =>
                    $reservasi->pegawai->nama ?? '-',

                'tanggal' =>
                    $reservasi->tanggal,

                'jam_mulai' =>
                    $reservasi->jam_mulai,

                'jam_selesai' =>
                    $reservasi->jam_selesai,

                'status' =>
                    $status,

                'catatan' =>
                    $reservasi->catatan,

                'tanggal_riwayat' =>
                    now(),
            ]
        );
    }
}