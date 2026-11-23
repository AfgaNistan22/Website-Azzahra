<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metode_pembayarans', function (Blueprint $table) {
            $table->string('tipe', 20)->default('Transfer')->after('metode');   // Transfer | QRIS
            $table->string('qris')->nullable()->after('logo');                   // path gambar QR di disk public
        });

        // Otomatis isi data default metode pembayaran saat migrasi dijalankan
        $defaultMetode = [
            ['metode' => 'Transfer BCA', 'tipe' => 'Transfer', 'norek' => '1234567890',   'an' => 'Azzahra Salon & Spa', 'aktif' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['metode' => 'Transfer BRI', 'tipe' => 'Transfer', 'norek' => '0987654321',   'an' => 'Azzahra Salon & Spa', 'aktif' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['metode' => 'DANA',         'tipe' => 'Transfer', 'norek' => '081234567890', 'an' => 'Azzahra Salon & Spa', 'aktif' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['metode' => 'QRIS',         'tipe' => 'QRIS',     'norek' => '-',            'an' => 'Azzahra Salon & Spa', 'aktif' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($defaultMetode as $item) {
            \Illuminate\Support\Facades\DB::table('metode_pembayarans')->updateOrInsert(
                ['metode' => $item['metode']],
                $item
            );
        }
    }

    public function down(): void
    {
        Schema::table('metode_pembayarans', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'qris']);
        });
    }
};