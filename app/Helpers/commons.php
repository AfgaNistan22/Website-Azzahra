<?php

if (!function_exists('format_rupiah')) {
    /**
     * Memformat angka menjadi format mata uang Rupiah standar Indonesia.
     * Contoh: 50000 -> "Rp 50.000"
     */
    function format_rupiah($angka): string
    {
        return 'Rp ' . number_format((float) $angka, 0, ',', '.');
    }
}
