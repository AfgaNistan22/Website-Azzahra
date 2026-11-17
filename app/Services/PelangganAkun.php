<?php

namespace App\Services;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

/**
 * Menjaga agar setiap akun berperan pelanggan selalu punya satu data Pelanggan.
 *
 * Dipakai di tiga tempat:
 * - saat registrasi (akun baru langsung terdaftar sebagai pelanggan),
 * - saat login (akun lama yang belum punya data pelanggan dibuatkan otomatis),
 * - saat membuat reservasi (jaring pengaman terakhir),
 * - perintah artisan pelanggan:sinkron (sekali jalan untuk semua akun lama).
 *
 * Semua pemanggilan memakai method yang sama, jadi aturannya selalu seragam.
 */
class PelangganAkun
{
    /** Daftar kolom tabel pelanggan, diambil sekali per proses. */
    private ?array $kolom = null;


    /** Data pelanggan yang sudah tertaut ke akun ini, atau null. */
    public function cari(User $user): ?Pelanggan
    {
        return Pelanggan::where('user_id', $user->id)->first();
    }

    /**
     * Mengembalikan data pelanggan milik akun. Urutannya:
     *
     * 1. Sudah tertaut: langsung dipakai.
     * 2. Ada data lama (dibuat admin saat pelanggan datang langsung) dengan email
     *    yang sama dan belum tertaut: ditautkan, bukan dibuat ganda.
     * 3. Selain itu: dibuat baru.
     *
     * @param bool $tautkanEmail  true = tautkan berdasarkan email tanpa syarat
     *                            verifikasi (dipakai perintah artisan yang dijalankan admin).
     */
    public function pastikan(User $user, bool $tautkanEmail = false): Pelanggan
    {
        $ada = $this->cari($user);

        if ($ada) {
            return $ada;
        }

        $lama = $this->cariDataLama($user, $tautkanEmail);

        if ($lama) {
            $lama->forceFill(['user_id' => $user->id])->save();

            return $lama;
        }

        try {
            return $this->buat($user);

        } catch (QueryException $e) {
            // Permintaan lain (misalnya login dari dua tab) baru saja membuat
            // data untuk akun yang sama. Indeks unik user_id menolak yang kedua,
            // jadi cukup ambil data yang sudah ada.
            $ada = $this->cari($user);

            if ($ada) {
                return $ada;
            }

            throw $e;
        }
    }

    /**
     * Mencari data pelanggan lama yang belum tertaut dan emailnya sama.
     *
     * Email pada form registrasi tidak diverifikasi. Bila penautan dilakukan
     * tanpa syarat, orang yang mendaftar dengan email milik orang lain bisa
     * "mewarisi" data pelanggan tersebut. Karena itu penautan otomatis hanya
     * berlaku untuk email yang sudah terverifikasi. Admin tetap dapat memaksa
     * lewat perintah: php artisan pelanggan:sinkron --tautkan-email
     */
    public function cariDataLama(User $user, bool $tautkanEmail = false): ?Pelanggan
    {
        if (!$this->punyaKolom('email') || empty($user->email)) {
            return null;
        }

        $terverifikasi = method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail();

        if (!$terverifikasi && !$tautkanEmail) {
            return null;
        }

        return Pelanggan::whereNull('user_id')
            ->where('email', $user->email)
            ->first();
    }


    /* =====================================================
     * PEMBANTU
     * ===================================================== */

    private function buat(User $user): Pelanggan
    {
        $data = [
            'user_id' => $user->id,
            'nama'    => $user->name,
        ];

        // Kolom berikut hanya diisi bila memang ada di tabel pelanggan
        $opsional = [
            'email'             => $user->email,
            // Akun dari login sosial (Google/Facebook) belum punya nomor telepon.
            // String kosong dipakai agar kolom NOT NULL tidak menggagalkan
            // pembuatan data pelanggan; nomor bisa dilengkapi di profil/booking.
            'no_telepon'        => $user->phone ?? '',
            'jk'                => 'Perempuan',   // salon khusus perempuan, sama seperti PelangganController
            'member'            => 'Tidak',
            'jumlah_kedatangan' => 0,
        ];

        foreach ($opsional as $kolom => $nilai) {
            if ($this->punyaKolom($kolom)) {
                $data[$kolom] = $nilai;
            }
        }

        // forceFill: user_id tidak perlu masuk $fillable pada model Pelanggan
        $pelanggan = new Pelanggan();
        $pelanggan->forceFill($data)->save();

        return $pelanggan;
    }

    private function punyaKolom(string $nama): bool
    {
        if ($this->kolom === null) {
            $this->kolom = Schema::getColumnListing((new Pelanggan())->getTable());
        }

        return in_array($nama, $this->kolom, true);
    }
}