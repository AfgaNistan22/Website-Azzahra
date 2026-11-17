<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {

            $table->id();

            $table->string('user_name');

            $table->string('aktivitas');

            $table->string('modul');

            $table->string('data_id')->nullable();

            $table->timestamp('waktu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};