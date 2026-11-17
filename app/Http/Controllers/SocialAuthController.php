<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\User;
use App\Services\PelangganAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

/**
 * Login / daftar memakai Google dan Facebook (Laravel Socialite).
 *
 * Aturan keamanan:
 * - Akun baru selalu menjadi pelanggan (role_id 2) berstatus approved,
 *   sama seperti registrasi biasa.
 * - Akun yang sudah ada HANYA boleh ditautkan bila itu akun pelanggan.
 *   Admin tidak bisa masuk lewat login sosial.
 * - Email dari Google harus terverifikasi.
 * - Parameter `state` diperiksa otomatis oleh Socialite (anti-CSRF).
 */
class SocialAuthController extends Controller
{
    private const PROVIDERS = ['google', 'facebook'];

    private const LABEL = [
        'google'   => 'Google',
        'facebook' => 'Facebook',
    ];

    public function __construct(private PelangganAkun $pelangganAkun)
    {
    }

    /**
     * Arahkan pengguna ke halaman izin penyedia.
     */
    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Terima balasan dari penyedia, lalu masukkan pengguna.
     */
    public function callback(Request $request, string $provider)
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);

        $label = self::LABEL[$provider];

        // Pengguna menekan "Batal" di halaman penyedia.
        if ($request->filled('error') || $request->filled('error_code')) {
            return $this->gagal("Login dengan {$label} dibatalkan.");
        }

        try {
            $sosial = Socialite::driver($provider)->user();
        } catch (Throwable $e) {
            Log::warning('Social Login: gagal mengambil data pengguna', [
                'provider'  => $provider,
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
            ]);

            return $this->gagal("Tidak dapat masuk dengan {$label}. Silakan coba lagi.");
        }

        $email = $sosial->getEmail();

        if (!$email) {
            return $this->gagal(
                "Akun {$label} Anda tidak membagikan alamat email. "
                . 'Silakan daftar menggunakan email.'
            );
        }

        // Google: tolak email yang belum terverifikasi.
        $mentah = is_array($sosial->user ?? null) ? $sosial->user : [];
        if ($provider === 'google' && array_key_exists('email_verified', $mentah) && !$mentah['email_verified']) {
            return $this->gagal('Email Google Anda belum terverifikasi.');
        }

        try {
            $user = $this->cariAtauBuat($provider, $sosial, $email);
        } catch (Throwable $e) {
            Log::error('Social Login Error', [
                'provider'  => $provider,
                'message'   => $e->getMessage(),
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);

            return $this->gagal('Terjadi kesalahan saat proses login. Silakan coba lagi.');
        }

        if ($user === null) {
            return $this->gagal(
                'Email ini terdaftar sebagai akun khusus. '
                . 'Silakan masuk menggunakan email dan password.'
            );
        }

        if ($user->status !== 'approved') {
            return $this->gagal('Akun belum disetujui atau tidak aktif.');
        }

        if (!$user->isPelanggan()) {
            return $this->gagal('Role akun tidak dikenali.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        History::create([
            'user_name' => $user->name,
            'aktivitas' => "Login ({$label})",
            'modul'     => 'Auth',
            'waktu'     => now(),
        ]);

        $this->sinkronPelanggan($user);

        return redirect()
            ->intended('/')
            ->with('success', 'Login berhasil. Selamat datang!');
    }

    /**
     * Cari akun berdasarkan penyedia + id; bila belum ada, tautkan ke akun
     * pelanggan dengan email yang sama, atau buat akun baru.
     * Mengembalikan null bila email dipakai akun non-pelanggan.
     */
    private function cariAtauBuat(string $provider, $sosial, string $email): ?User
    {
        $id = (string) $sosial->getId();

        $user = User::where('provider', $provider)
            ->where('provider_id', $id)
            ->first();

        if ($user) {
            return $user;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            if (!$user->isPelanggan()) {
                return null;
            }

            // Tautkan hanya bila belum terhubung ke penyedia lain.
            if (empty($user->provider_id)) {
                $user->forceFill([
                    'provider'    => $provider,
                    'provider_id' => $id,
                    'avatar'      => $sosial->getAvatar(),
                ])->save();
            }

            return $user;
        }

        return DB::transaction(function () use ($provider, $sosial, $email, $id) {
            $user = new User();

            // forceFill: kolom sosial tidak perlu masuk $fillable.
            $user->forceFill([
                'name'        => $sosial->getName() ?: Str::before($email, '@'),
                'email'       => $email,
                'phone'       => null,
                'password'    => Hash::make(Str::random(40)),
                'role_id'     => 2,
                'status'      => 'approved',
                'provider'    => $provider,
                'provider_id' => $id,
                'avatar'      => $sosial->getAvatar(),
            ])->save();

            History::create([
                'user_name' => $user->name,
                'aktivitas' => 'Registrasi (' . self::LABEL[$provider] . ')',
                'modul'     => 'Auth',
                'waktu'     => now(),
            ]);

            return $user;
        });
    }

    private function sinkronPelanggan(User $user): void
    {
        try {
            $this->pelangganAkun->pastikan($user);
        } catch (Throwable $e) {
            Log::error('Sinkron Pelanggan Error', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function gagal(string $pesan)
    {
        return redirect()->route('login')->with('error', $pesan);
    }
}