<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penelitian_dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dosen', 20);
            $table->string('nama_dosen', 100);
            $table->string('jenis_jurnal', 100);
            $table->string('jenis_penelitian', 100);
            $table->string('nama_jurnal', 200);
            $table->string('link_jurnal', 255)->nullable();
            $table->string('berkas_sertifikat', 255)->nullable();
            $table->string('berkas_paper', 255)->nullable();
            $table->string('proposal', 255)->nullable();
            $table->string('laporan', 255)->nullable();
            $table->string('lainnya', 255)->nullable();
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penelitian_dosens');
    }
};
