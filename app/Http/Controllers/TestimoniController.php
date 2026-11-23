<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Http\Request;

/**
 * Testimoni pelanggan.
 * - index   : publik (tanpa login), bisa disaring per bintang
 * - store   : wajib login; satu akun satu ulasan, mengirim lagi = memperbarui
 * - destroy : pemilik ulasan atau admin
 * View: resources/views/pages/testimoni/index.blade.php
 */
class TestimoniController extends Controller
{
    public function index(Request $request)
    {
        $bintang = (int) $request->query('bintang');
        $bintang = ($bintang >= 1 && $bintang <= 5) ? $bintang : null;

        $total   = Testimoni::count();
        $rata    = $total ? round((float) Testimoni::avg('rating'), 1) : 0;
        $sebaran = Testimoni::selectRaw('rating, COUNT(*) as jumlah')
            ->groupBy('rating')
            ->pluck('jumlah', 'rating');

        $daftar = Testimoni::with('user:id,name,photo')
            ->when($bintang, fn ($q) => $q->where('rating', $bintang))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $milikku = $request->user()
            ? Testimoni::where('user_id', $request->user()->id)->first()
            : null;

        return view('pages.testimoni.index', compact('daftar', 'total', 'rata', 'sebaran', 'bintang', 'milikku'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rating'   => ['required', 'integer', 'between:1,5'],
            'komentar' => ['required', 'string', 'min:10', 'max:600'],
        ], [
            'rating.required'   => 'Pilih jumlah bintang dulu ya.',
            'rating.between'    => 'Penilaian harus antara 1 sampai 5 bintang.',
            'komentar.required' => 'Tulis sedikit ceritamu ya.',
            'komentar.min'      => 'Komentar minimal 10 karakter.',
            'komentar.max'      => 'Komentar maksimal 600 karakter.',
        ]);

        $user = $request->user();

        // Validasi bisnis: Ulasan hanya untuk pelanggan yang memiliki riwayat reservasi
        if (!$user->isAdmin()) {
            $pelanggan = $user->pelanggan;
            $sudahReservasi = $pelanggan && \App\Models\Reservasi::where('pelanggan_id', $pelanggan->id)
                ->whereIn('status', ['Selesai', 'Dikonfirmasi'])
                ->exists();

            if (!$sudahReservasi) {
                return back()
                    ->withInput()
                    ->withErrors(['komentar' => 'Ulasan hanya dapat dikirimkan oleh pelanggan yang telah memiliki reservasi di Azzahra Salon & Spa.']);
            }
        }

        $ulasan = Testimoni::updateOrCreate(
            ['user_id' => $user->id],
            ['rating' => $data['rating'], 'komentar' => trim($data['komentar'])]
        );

        $pesan = $ulasan->wasRecentlyCreated
            ? 'Makasih! Ulasanmu sudah tampil.'
            : 'Ulasanmu sudah diperbarui.';

        return redirect(route('testimoni.index') . '#daftar-ulasan')->with('success', $pesan);
    }

    public function destroy(Request $request, Testimoni $testimoni)
    {
        $user = $request->user();

        abort_unless($user->id === $testimoni->user_id || $user->isAdmin(), 403);

        $testimoni->delete();

        return back()->with('success', 'Ulasan sudah dihapus.');
    }
}