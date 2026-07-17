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
        Schema::create('tendiks', function (Blueprint $table) {
            $table->id();
            $table->string('nip_nik')->nullable();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jabatan_tugas')->nullable();
            $table->string('status_pegawai')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('bukti_sertifikasi')->nullable();
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
        Schema::dropIfExists('tendiks');
    }
};
