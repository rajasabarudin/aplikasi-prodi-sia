<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pkm_dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dosen', 20);
            $table->string('nama_dosen', 100);
            $table->string('tema_pkm', 200);
            $table->string('mitra', 200);
            $table->string('jenis_pkm', 50);
            $table->string('sumber_iptek', 100);
            $table->string('nim_mhs', 20)->nullable();
            $table->string('nama_mahasiswa', 100)->nullable();
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->string('link_dokumen', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pkm_dosens');
    }
};
