<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_referensis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matakuliah');
            $table->enum('jenis', ['utama', 'pendukung'])->default('utama');
            $table->string('penulis');
            $table->integer('tahun');
            $table->string('judul');
            $table->string('penerbit')->nullable();
            $table->string('kota')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();

            $table->foreign('kode_matakuliah')->references('kode_matakuliah')->on('matakuliahs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_referensis');
    }
};
