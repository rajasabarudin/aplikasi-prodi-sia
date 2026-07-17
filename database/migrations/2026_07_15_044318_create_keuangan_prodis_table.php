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
        Schema::create('keuangan_prodis', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_akademik')->unique();
            $table->integer('jumlah_mahasiswa_aktif')->default(0);
            $table->bigInteger('dana_pendidikan')->default(0);
            $table->bigInteger('dana_penelitian')->default(0);
            $table->bigInteger('dana_pkm')->default(0);
            $table->bigInteger('dana_investasi')->default(0);
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
        Schema::dropIfExists('keuangan_prodis');
    }
};
