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
        Schema::create('kerjasama', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_mou');
            $table->string('nomor_mou_ubsi');
            $table->string('nomor_mou_mitra')->nullable();
            $table->string('nama_mitra');
            $table->string('ketua_mewakili');
            $table->string('no_wa_mitra')->nullable();
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
        Schema::dropIfExists('kerjasama');
    }
};
