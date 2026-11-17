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
    }

    public function down(): void
    {
        Schema::table('metode_pembayarans', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'qris']);
        });
    }
};