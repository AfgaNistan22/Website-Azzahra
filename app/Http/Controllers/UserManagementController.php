<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Controller Pengelolaan Akun Pengguna / User (Admin & Pelanggan)
 * Fitur:
 * - Menampilkan daftar akun, pencarian, dan filter role/status
 * - Reset kata sandi (bantuan admin) + tautan kirim ke WhatsApp
 * - Ubah status aktif / nonaktif
 * - Hapus akun pengguna
 */
class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $cari   = trim($request->query('cari', ''));
        $roleId = $request->query('role');
        $status = $request->query('status');

        $query = User::with(['role', 'pelanggan'])->latest();

        if ($cari !== '') {
            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                  ->orWhere('email', 'like', "%{$cari}%")
                  ->orWhere('phone', 'like', "%{$cari}%")
                  ->orWhereHas('pelanggan', function ($pq) use ($cari) {
                      $pq->where('nama', 'like', "%{$cari}%")
                        ->orWhere('no_telepon', 'like', "%{$cari}%");
                  });
            });
        }

        if ($roleId !== null && $roleId !== '') {
            $query->where('role_id', (int) $roleId);
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => User::count(),
            'pelanggan' => User::where('role_id', 2)->count(),
            'admin'     => User::where('role_id', 1)->count(),
            'google'    => User::whereNotNull('provider')->count(),
        ];

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'stats', 'roles', 'cari', 'roleId', 'status'));
    }

    /**
     * Reset password akun pengguna oleh Admin
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password_baru' => ['nullable', 'string', 'min:8'],
        ]);

        // Jika admin tidak memasukkan kata sandi spesifik, buatkan acak sementara
        $passwordBaru = $request->filled('password_baru')
            ? $request->input('password_baru')
            : 'Azzahra' . rand(1000, 9999) . '#';

        $user->update([
            'password' => Hash::make($passwordBaru),
        ]);

        // Ambil nomor WhatsApp untuk tautan kirim pesan
        $phone = $user->phone ?? $user->pelanggan?->no_telepon ?? '';
        $waNumber = preg_replace('/[^0-9]/', '', (string) $phone);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        $pesanWa = "Halo {$user->name},\n\nKata sandi akun Azzahra Salon & Spa Anda telah diatur ulang oleh Admin.\n\nKata Sandi Sementara: *{$passwordBaru}*\nEmail: {$user->email}\n\nSilakan masuk dan segera ubah kata sandi Anda melalui menu Profil.";
        $linkWa = !empty($waNumber) ? 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($pesanWa) : null;

        return back()->with('reset_info', [
            'nama'          => $user->name,
            'email'         => $user->email,
            'password_baru' => $passwordBaru,
            'link_wa'       => $linkWa,
            'no_wa'         => $phone,
        ])->with('success', "Kata sandi untuk pengguna '{$user->name}' berhasil direset.");
    }

    /**
     * Ubah status aktif / nonaktif akun
     */
    public function updateStatus(Request $request, User $user)
    {
        // Cegah admin menonaktifkan akunnya sendiri yang sedang aktif
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
        }

        $request->validate([
            'status' => ['required', 'in:approved,pending,rejected'],
        ]);

        $user->update([
            'status' => $request->input('status'),
        ]);

        return back()->with('success', "Status akun '{$user->name}' berhasil diperbarui menjadi {$request->input('status')}.");
    }

    /**
     * Hapus akun pengguna
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.');
        }

        $nama = $user->name;
        $user->delete();

        return back()->with('success', "Akun pengguna '{$nama}' berhasil dihapus.");
    }
}
