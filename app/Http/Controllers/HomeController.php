<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\KategoriPerawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = trim($request->query('search', ''));
            $kategoriId = $request->query('kategori');
            $tampilkanSemua = $request->query('semua') == '1';

            // Ambil semua kategori yang aktif.
            $categories = KategoriPerawatan::where('status', 'Aktif')
                ->orderBy('nama')
                ->get();

            $selectedCategory = null;

            // Query layanan beserta relasi kategorinya.
            $servicesQuery = Perawatan::with('kategori');

            // Prioritas pertama: kategori yang dipilih.
            if (
                $kategoriId !== null &&
                ctype_digit((string) $kategoriId)
            ) {
                $selectedCategory = KategoriPerawatan::where('status', 'Aktif')
                    ->find((int) $kategoriId);

                if ($selectedCategory) {
                    $servicesQuery->where(
                        'kategori_id',
                        $selectedCategory->id
                    );
                }
            }

            // Prioritas kedua: pencarian berdasarkan kata kunci.
            elseif ($search !== '' && !$tampilkanSemua) {
                $servicesQuery->where(function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%')
                        ->orWhereHas('kategori', function ($categoryQuery) use ($search) {
                            $categoryQuery->where(
                                'nama',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                });
            }

            // Jika kategori dan kata kunci tidak dipilih,
            // semua layanan akan ditampilkan.
            $services = $servicesQuery->latest()->get();

            $totalLayanan = Perawatan::count();

            // Buka halaman pencarian jika:
            // 1. Kategori dipilih.
            // 2. Kata kunci pencarian diisi.
            // 3. Parameter semua=1 digunakan.
            if (
                $kategoriId !== null ||
                $search !== '' ||
                $tampilkanSemua
            ) {
                return view('pages.partials.search', compact(
                    'services',
                    'categories',
                    'totalLayanan',
                    'search',
                    'kategoriId',
                    'selectedCategory'
                ));
            }

            // Tampilan awal halaman beranda.
            return view('pages.partials.home', compact(
                'services',
                'categories',
                'totalLayanan',
                'search'
            ));

        } catch (\Throwable $e) {
            Log::error('HomeController@index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'Terjadi kesalahan saat memuat layanan.'
                );
        }
    }

    public function detail($id)
    {
        try{
             $layanan = Perawatan::with('kategori')->findOrFail($id);

            return view('pages.partials.detail', compact('layanan'));
        } catch (\Throwable $e) {
            Log::error('HomeController@index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('home')
                ->with(
                    'error',
                    'Terjadi kesalahan saat memuat perawatan.'
                );
        }
    }
}