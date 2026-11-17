<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Pengelolaan galeri oleh admin: unggah banyak foto sekaligus, ubah, hapus.
 * View: resources/views/admin/galeri/index.blade.php
 * Foto disimpan di storage/app/public/foto_galeri (butuh: php artisan storage:link)
 */
class GaleriAdminController extends Controller
{
    public function index()
    {
        return view('admin.galeri.index', [
            'galeri'   => Galeri::orderBy('urutan')->orderByDesc('id')->paginate(24),
            'kategori' => Galeri::whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori'),
        ]);
    }

    /** Unggah satu atau banyak foto. Kategori dan judul berlaku untuk semua foto di unggahan itu. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'foto'     => ['required', 'array', 'min:1', 'max:20'],
            'foto.*'   => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'judul'    => ['nullable', 'string', 'max:150'],
            'kategori' => ['nullable', 'string', 'max:60'],
            'status'   => ['required', 'in:Aktif,Nonaktif'],
        ], [
            'foto.required' => 'Pilih minimal satu foto.',
            'foto.*.image'  => 'Berkas harus berupa gambar.',
            'foto.*.mimes'  => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'foto.*.max'    => 'Ukuran tiap foto maksimal 4 MB.',
        ]);

        $urutanAwal = (int) Galeri::max('urutan');

        foreach ($request->file('foto') as $i => $berkas) {
            Galeri::create([
                'judul'    => $data['judul'] ?? null,
                'kategori' => $data['kategori'] ?? null,
                'foto'     => $berkas->store('foto_galeri', 'public'),   // hasil: foto_galeri/xxxx.jpg
                'urutan'   => $urutanAwal + $i + 1,
                'status'   => $data['status'],
            ]);
        }

        return back()->with('success', count($request->file('foto')) . ' foto berhasil ditambahkan ke galeri.');
    }

    public function update(Request $request, Galeri $galeri)
    {
        $data = $request->validate([
            'judul'    => ['nullable', 'string', 'max:150'],
            'kategori' => ['nullable', 'string', 'max:60'],
            'urutan'   => ['nullable', 'integer', 'min:0', 'max:65535'],
            'status'   => ['required', 'in:Aktif,Nonaktif'],
            'foto'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            $baru = $request->file('foto')->store('foto_galeri', 'public');
            Storage::disk('public')->delete($galeri->foto);
            $data['foto'] = $baru;
        } else {
            unset($data['foto']);
        }

        $data['urutan'] = $data['urutan'] ?? $galeri->urutan;

        $galeri->update($data);

        return back()->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri)
    {
        Storage::disk('public')->delete($galeri->foto);
        $galeri->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}
