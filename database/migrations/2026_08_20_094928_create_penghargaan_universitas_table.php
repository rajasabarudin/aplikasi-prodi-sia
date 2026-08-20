<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penghargaan_universitas', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 4);
            $table->string('nama_mitra');
            $table->string('nomor_penghargaan')->nullable();
            $table->string('link_dokumen_penghargaan')->nullable();
            $table->string('link_berita')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penghargaan_universitas');
    }
};
