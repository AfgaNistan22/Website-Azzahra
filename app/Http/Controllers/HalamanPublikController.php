<?php
// app/Http/Controllers/HalamanPublikController.php

namespace App\Http\Controllers;

use App\Models\Promo;     // sesuaikan dengan model Anda
use App\Models\Galeri;    // sesuaikan, atau hapus bila belum ada
use Illuminate\Support\Facades\Schema;

class HalamanPublikController extends Controller
{
    public function promo()
    {
        $promo = collect();

        // Sesuaikan nama model promo Anda (cek di folder app/Models)
        $modelPromo = Promo::class;

        if (class_exists($modelPromo) && Schema::hasTable((new $modelPromo)->getTable())) {
            $promo = $modelPromo::query()->latest()->get();
        }

        return view('pages.promo.index2', compact('promo'));
    }

    public function sejarah()
    {
        return view('pages.tentang.sejarah');
    }

    public function kontak()
    {
        return view('pages.tentang.kontak');
    }

    public function galeri()
    {
        $galeri = Galeri::aktif()
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get();
 
        return view('pages.galeri.index', compact('galeri'));
    }
}