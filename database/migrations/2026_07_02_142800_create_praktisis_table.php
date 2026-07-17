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
        Schema::create('praktisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_praktisi');
            $table->string('kode_matakuliah');
            $table->string('kelas');
            $table->string('link_ijazah')->nullable();
            $table->string('link_sertifikasi')->nullable();
            $table->string('link_dokumen')->nullable();
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
        Schema::dropIfExists('praktisis');
    }
};
