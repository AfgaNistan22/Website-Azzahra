<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

/**
 * Halaman publik Galeri (tanpa login).
 * View: resources/views/pages/galeri.blade.php
 */
class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Galeri::aktif()
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get();

        return view('pages.galeri', compact('galeri'));
    }
}