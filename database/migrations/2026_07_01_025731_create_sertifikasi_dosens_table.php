<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sertifikasi_dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dosen');
            $table->string('nama_dosen');
            $table->string('nama_sertifikasi');
            $table->string('penerbit');
            $table->string('link_dokumen')->nullable();
            $table->timestamps();

            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sertifikasi_dosens');
    }
};
