<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matakuliah');
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_penyusunan')->nullable();
            $table->string('dosen_pengembang')->nullable();
            $table->string('koordinator')->nullable();
            $table->string('kaprodi')->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->integer('bobot_kehadiran')->default(20);
            $table->integer('bobot_tugas')->default(25);
            $table->integer('bobot_project')->default(55);
            $table->boolean('asesmen_tertulis')->default(false);
            $table->boolean('asesmen_lisan')->default(false);
            $table->boolean('asesmen_kinerja')->default(false);
            $table->boolean('asesmen_portofolio')->default(false);
            $table->timestamps();

            $table->foreign('kode_matakuliah')->references('kode_matakuliah')->on('matakuliahs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps');
    }
};
