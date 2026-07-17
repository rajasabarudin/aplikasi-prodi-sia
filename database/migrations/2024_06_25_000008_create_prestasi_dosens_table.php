<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prestasi_dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dosen', 20);
            $table->string('nama_dosen', 100);
            $table->string('nama_prestasi', 200);
            $table->string('tahun', 10);
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->string('penyelenggara', 200);
            $table->enum('level_prestasi', ['lokal', 'nasional', 'internasional']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasi_dosens');
    }
};
