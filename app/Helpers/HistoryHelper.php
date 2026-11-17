<?php

namespace App\Helpers;

use App\Models\History;
use Illuminate\Support\Facades\Auth;

class HistoryHelper
{
    public static function log($aktivitas,$modul,$id=null)
    {
        History::create([
            'user_name' => Auth::user()->name ?? 'System',
            'aktivitas' => $aktivitas,
            'modul'     => $modul,
            'data_id'   => $id,
            'waktu'     => now()
        ]);
    }
}