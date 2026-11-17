<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Services\PelangganAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil berdasarkan role pengguna.
     */
    public function index(PelangganAkun $akun)
    {
        $user = auth()->user();

        // Pastikan pengguna sudah login.
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin: resources/views/pages/profile/index.blade.php
        if ((int) $user->role_id === 1) {
            return view('pages.profile.index');
        }

        // Pelanggan: resources/views/pages/partials/profile-index.blade.php
        // Data pelanggan dikirim supaya form bisa diisi (null bila gagal dibuat).
        $pelanggan = null;

        try {
            $pelanggan = $akun->pastikan($user);
        } catch (\Throwable $e) {
            Log::error('Profil Pelanggan Error', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }

        return view('pages.partials.profile-index', [
            'pelanggan' => $pelanggan,
        ]);
    }

    /**
     * Memperbarui profil pengguna yang sedang login.
     */
    public function update(Request $request, $id, PelangganAkun $akun)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Pengguna hanya boleh mengubah profil miliknya sendiri.
        if ((int) $user->id !== (int) $id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah profil ini.');
        }

        // Pelanggan punya form yang lebih lengkap, jalurnya dipisah.
        if ((int) $user->role_id !== 1) {
            return $this->updatePelanggan($request, $akun);
        }

        // ------------------------------------------------------------
        //  ADMIN (tidak diubah dari versi sebelumnya)
        // ------------------------------------------------------------

        // Validasi input.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ]);

        try {
            $user->name = $validated['name'];

            // Jika ada foto baru yang diunggah.
            if ($request->hasFile('photo')) {
                $oldPhoto = $user->photo;

                // Simpan foto baru ke storage/app/public/profile.
                $newPhoto = $request->file('photo')
                    ->store('profile', 'public');

                if (!$newPhoto) {
                    throw new \RuntimeException(
                        'Foto profil gagal disimpan.'
                    );
                }

                // Simpan path foto baru ke database.
                $user->photo = $newPhoto;
            }

            $user->save();

            // Hapus foto lama setelah perubahan berhasil disimpan.
            if (
                $request->hasFile('photo') &&
                !empty($oldPhoto) &&
                Storage::disk('public')->exists($oldPhoto)
            ) {
                Storage::disk('public')->delete($oldPhoto);
            }

            // Redirect sesuai role pengguna.
            if ((int) $user->role_id === 1) {
                return redirect()
                    ->route('profile.admin')
                    ->with('success', 'Profil berhasil diperbarui.');
            } else {
                return redirect()
                    ->route('profile.index')
                    ->with('success', 'Profil berhasil diperbarui.');
            }

        } catch (\Throwable $e) {
            Log::error('Update Profile Error', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memperbarui profil.'
                );
        }
    }

    /**
     * Simpan data diri pelanggan (form seperti edit pelanggan di admin).
     *
     * jumlah_kedatangan, terakhir_datang, dan member sengaja tidak diterima
     * dari form karena itu urusan admin.
     */
    private function updatePelanggan(Request $request, PelangganAkun $akun)
    {
        $user = auth()->user();

        $data = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'tgl_lahir'  => ['required', 'date', 'before_or_equal:today'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'alamat'     => ['required', 'string', 'max:500'],
            'foto'       => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nama.required'             => 'Nama wajib diisi.',
            'tgl_lahir.required'        => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melewati hari ini.',
            'no_telepon.required'       => 'Nomor telepon wajib diisi.',
            'email.required'            => 'Email wajib diisi.',
            'email.email'               => 'Format email tidak valid.',
            'email.unique'              => 'Email sudah dipakai akun lain.',
            'alamat.required'           => 'Alamat wajib diisi.',
            'foto.image'                => 'File foto harus berupa gambar.',
            'foto.mimes'                => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max'                  => 'Ukuran foto maksimal 2 MB.',
        ]);

        $fotoBaru = null;

        try {
            $pelanggan = $akun->pastikan($user);

            $fotoPelangganLama = $pelanggan->foto;   // nama file di storage/foto_pelanggan
            $fotoUserLama      = $user->photo;       // path relatif di disk public

            if ($request->hasFile('foto')) {
                $fotoBaru = $request->file('foto')->hashName();
                $request->file('foto')->storeAs('foto_pelanggan', $fotoBaru, 'public');
            }

            DB::transaction(function () use ($data, $user, $pelanggan, $fotoBaru) {
                $isi = [
                    'nama'       => $data['nama'],
                    'tgl_lahir'  => $data['tgl_lahir'],
                    'no_telepon' => $data['no_telepon'],
                    'alamat'     => $data['alamat'],
                ];

                // Kolom email di tabel pelanggan belum tentu ada.
                if (Schema::hasColumn($pelanggan->getTable(), 'email')) {
                    $isi['email'] = $data['email'];
                }

                if ($fotoBaru) {
                    $isi['foto'] = $fotoBaru;
                }

                $pelanggan->update($isi);

                // Tabel users punya nama, email, dan phone sendiri, jadi ikut disamakan.
                $user->name  = $data['nama'];
                $user->email = $data['email'];
                $user->phone = $data['no_telepon'];

                // File yang sama dipakai juga sebagai foto akun (navbar, sidebar).
                if ($fotoBaru) {
                    $user->photo = 'foto_pelanggan/' . $fotoBaru;
                }

                $user->save();

                History::create([
                    'user_name' => $user->name,
                    'aktivitas' => 'Ubah Profil',
                    'modul'     => 'Profile',
                    'waktu'     => now(),
                ]);
            });

            // Foto lama dihapus hanya setelah database berhasil tersimpan.
            if ($fotoBaru) {
                $lamaPelanggan = $fotoPelangganLama ? 'foto_pelanggan/' . $fotoPelangganLama : null;

                foreach (array_unique(array_filter([$lamaPelanggan, $fotoUserLama])) as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

        } catch (\Throwable $e) {
            // Foto baru yang sudah terlanjur tersimpan dibuang supaya tidak menumpuk.
            if ($fotoBaru) {
                Storage::disk('public')->delete('foto_pelanggan/' . $fotoBaru);
            }

            Log::error('Update Profile Pelanggan Error', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()
                ->withInput($request->except('foto'))
                ->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }

        // Dijalankan hanya bila penyimpanan berhasil. Berada di luar try agar
        // masalah pada redirect tidak dianggap gagal simpan dan tidak menghapus foto.
        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}