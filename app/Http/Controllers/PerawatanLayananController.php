<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\KategoriPerawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerawatanLayananController extends Controller
{
    public function index(Request $request)
    {
        $kategoriTerpilih = $request->query('kategori');
        $pencarian = trim((string) $request->query('cari', ''));

        $kategori = KategoriPerawatan::query()
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();

        $query = Perawatan::query()
            ->with('kategori')
            ->whereHas('kategori', function ($q) {
                $q->where('status', 'Aktif');
            });

        if ($kategoriTerpilih) {
            $query->where('kategori_id', $kategoriTerpilih);
        }

        if ($pencarian !== '') {
            $query->where(function ($q) use ($pencarian) {
                $q->where('nama', 'like', "%{$pencarian}%")
                    ->orWhere('deskripsi', 'like', "%{$pencarian}%");
            });
        }

        $perawatan = $query
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        return view('pages.perawatan-layanan.index', compact(
            'kategori',
            'perawatan',
            'kategoriTerpilih',
            'pencarian'
        ));
    }

    public function detail($id)
    {
        try{
             $layanan = Perawatan::with('kategori')->findOrFail($id);

            return view('pages.partials.detail', compact('layanan'));
        } catch (\Throwable $e) {
            Log::error('PerawatanLayananController@index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('perawatan-layanan.index')
                ->with(
                    'error',
                    'Terjadi kesalahan saat memuat perawatan.'
                );
        }
    }
}