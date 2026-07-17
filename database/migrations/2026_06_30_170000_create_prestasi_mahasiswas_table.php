<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestasi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_prestasi');
            $table->string('tahun');
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->string('penyelenggara');
            $table->string('bidang_prestasi'); // Akademik, Non Akademik, Akademik Non Lomba
            $table->string('prestasi_diraih'); // Juara 1, Juara 2, Juara 3, Harapan 1, Harapan 2, Partisipan
            $table->string('level_prestasi'); // Lokal, Wilayah, Nasional, Internasional
            $table->string('link_dokumen')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_mahasiswas');
    }
};
