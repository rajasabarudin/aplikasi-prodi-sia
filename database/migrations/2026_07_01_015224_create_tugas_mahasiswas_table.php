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
        Schema::create('tugas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->string('nama_matakuliah');
            $table->string('link_dokumen')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tugas_mahasiswas');
    }
};
