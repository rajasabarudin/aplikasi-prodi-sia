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
        Schema::create('kegiatan_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_kegiatan');
            $table->string('jenis_kegiatan');
            $table->date('tgl_kegiatan');
            $table->string('penyelenggara');
            $table->string('link_bukti_kegiatan')->nullable();
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
        Schema::dropIfExists('kegiatan_mahasiswas');
    }
};
