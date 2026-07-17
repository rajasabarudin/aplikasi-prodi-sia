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
        Schema::create('pks_ia', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mitra');
            $table->date('tgl_pks');
            $table->string('no_pks_ubsi');
            $table->string('no_pks_mitra')->nullable();
            $table->string('tema_pks');
            $table->enum('kategori', ['Pendidikan', 'PKM', 'Penelitian']);
            $table->enum('level_pks', ['Lokal/Wilayah', 'Nasional', 'Internasional']);
            $table->string('file_pks')->nullable();
            $table->string('file_ia')->nullable();
            $table->string('file_tambahan')->nullable();
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
        Schema::dropIfExists('pks_ia');
    }
};
