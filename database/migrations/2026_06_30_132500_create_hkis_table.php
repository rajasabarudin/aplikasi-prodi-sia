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
        Schema::create('hkis', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->nullable();
            $table->string('no_permohonan');
            $table->date('tgl_permohonan');
            $table->string('jenis_ciptaan');
            $table->string('judul_ciptaan');
            $table->string('kode_dosen')->nullable();
            $table->string('nama_dosen')->nullable();
            $table->string('link_dokumen')->nullable();
            $table->timestamps();

            // Foreign key to mahasiswas table
            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hkis');
    }
};
