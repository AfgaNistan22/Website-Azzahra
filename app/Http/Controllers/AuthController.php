<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Models\History;
use App\Services\PelangganAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private PelangganAkun $pelangganAkun)
    {
    }

    /**
     * Tampilkan halaman login.
     */
    public function login()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('pages.auth.login');
    }

    /**
     * Tampilkan halaman registrasi.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('pages.auth.register');
    }

    /**
     * Proses registrasi pelanggan.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.max' => 'Email maksimal 255 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        try {
            $user = DB::transaction(function () use ($validated) {
                // Akun yang didaftarkan adalah pelanggan.
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($validated['password']),
                    'role_id' => 2,
                    'status' => 'approved',
                ]);

                History::create([
                    'user_name' => $user->name,
                    'aktivitas' => 'Registrasi',
                    'modul' => 'Auth',
                    'waktu' => now(),
                ]);

                return $user;
            });

            // Daftarkan juga sebagai pelanggan salon.
            $this->sinkronPelanggan($user);

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Pendaftaran berhasil. Silakan login menggunakan akun Anda.'
                );

        } catch (Throwable $e) {
            Log::error('Register Error', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->with(
                    'error',
                    'Terjadi kesalahan saat proses pendaftaran. Silakan coba lagi.'
                );
        }
    }

    /**
     * Proses login pengguna.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            if (!Auth::attempt($credentials)) {
                return back()
                    ->withErrors([
                        'email' => 'Email atau password salah.',
                    ])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            $user = Auth::user();

            // Hanya akun yang disetujui yang boleh login.
            if ($user->status !== 'approved') {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/')
                    ->withErrors([
                        'email' => 'Akun belum disetujui atau tidak aktif.',
                    ])
                    ->onlyInput('email');
            }

            // Catat aktivitas login.
            History::create([
                'user_name' => $user->name,
                'aktivitas' => 'Login',
                'modul' => 'Auth',
                'waktu' => now(),
            ]);

            // Akun lama yang belum punya data pelanggan dibuatkan sekarang.
            $this->sinkronPelanggan($user);

            // Admin masuk ke dashboard admin.
            if ($user->isAdmin()) {
                return redirect()
                    ->intended('/')
                    ->with('success', 'Login berhasil. Selamat datang, Admin!');
            }

            // Pelanggan masuk ke halaman profil pelanggan.
            if ($user->isPelanggan()) {
                return redirect('/')
                    ->with('success', 'Login berhasil. Selamat datang!');
            }

            // Logout jika role tidak dikenali.
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->withErrors([
                    'email' => 'Role akun tidak dikenali.',
                ]);

        } catch (Throwable $e) {
            Log::error('Login Error', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat proses login.'
                )
                ->onlyInput('email');
        }
    }

    /**
     * Pastikan akun pelanggan punya data Pelanggan.
     *
     * Dibungkus sendiri supaya kegagalan di sini (misalnya kolom wajib di
     * tabel pelanggan yang belum terisi) tidak menggagalkan registrasi atau
     * login. Kegagalan dicatat di log, dan pendaftaran diulang otomatis pada
     * login berikutnya atau saat pelanggan membuat reservasi.
     */
    private function sinkronPelanggan(User $user): void
    {
        try {
            if ($user->isPelanggan()) {
                $this->pelangganAkun->pastikan($user);
            }
        } catch (Throwable $e) {
            Log::error('Sinkron Pelanggan Error', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Redirect berdasarkan role untuk pengguna yang sudah login.
     */
    private function redirectByRole()
    {
        $user = Auth::user();

        if (!$user || $user->status !== 'approved') {
            Auth::logout();

            return redirect('/')
                ->with(
                    'error',
                    'Akun belum disetujui atau tidak aktif.'
                );
        }

        if ($user->isAdmin()) {
            return redirect('/dashboard');
        }

        if ($user->isPelanggan()) {
            return redirect('/profile');
        }

        Auth::logout();

        return redirect('/')
            ->withErrors([
                'email' => 'Role akun tidak dikenali.',
            ]);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                History::create([
                    'user_name' => $user->name,
                    'aktivitas' => 'Logout',
                    'modul' => 'Auth',
                    'waktu' => now(),
                ]);
            }

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->with('success', 'Anda berhasil logout.');

        } catch (Throwable $e) {
            Log::error('Logout Error', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect('/')
                ->with('error', 'Terjadi kesalahan saat logout.');
        }
    }

    /**
     * Tampilkan halaman ubah password sesuai role.
     */
    public function change_pass()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Halaman ubah password admin.
        if ($user->isAdmin()) {
            return view('pages.profile.change');
        }

        // Halaman ubah password pelanggan.
        if ($user->isPelanggan()) {
            return view('pages.partials.profile-change');
        }

        abort(403, 'Role pengguna tidak dikenali.');
    }

    /**
     * Ubah password pengguna yang sedang login.
     */
    public function change_password(
        ChangePasswordRequest $request,
        $userid
    ) {
        try {
            // Pengguna hanya boleh mengubah password miliknya sendiri.
            if ((int) Auth::id() !== (int) $userid) {
                abort(
                    403,
                    'Anda tidak dapat mengubah password pengguna lain.'
                );
            }

            $user = Auth::user();

            if (!Hash::check(
                $request->old_password,
                $user->password
            )) {
                return back()
                    ->withErrors([
                        'old_password' => 'Password lama tidak valid.',
                    ])
                    ->withInput();
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            History::create([
                'user_name' => $user->name,
                'aktivitas' => 'Ubah Password',
                'modul' => 'Profile',
                'waktu' => now(),
            ]);

            return back()
                ->with('success', 'Berhasil mengubah password.');

        } catch (Throwable $e) {
            Log::error('Change Password Error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            return back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengubah password.'
                );
        }
    }
}