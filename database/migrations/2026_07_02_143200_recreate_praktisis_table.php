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
        Schema::dropIfExists('praktisis');

        Schema::create('praktisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_praktisi');
            $table->text('kode_matakuliah'); // stores JSON array of course codes
            $table->text('kelas');           // stores JSON array of class names
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
