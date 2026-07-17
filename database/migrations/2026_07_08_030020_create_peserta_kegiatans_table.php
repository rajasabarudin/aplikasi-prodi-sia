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
        Schema::create('peserta_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->string('nama');
            $table->string('identifier');
            $table->string('kategori')->default('Mahasiswa');
            $table->string('barcode_token')->unique();
            $table->timestamp('jam_masuk')->nullable();
            $table->timestamp('jam_pulang')->nullable();
            $table->string('status_kehadiran')->default('absen'); // absen, hadir_masuk, hadir_lengkap
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peserta_kegiatans');
    }
};
