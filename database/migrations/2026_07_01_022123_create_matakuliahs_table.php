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
        Schema::create('matakuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matakuliah')->unique();
            $table->string('nama_matakuliah');
            $table->integer('sks');
            $table->string('jenis_matakuliah'); // Ciri Nasional, Ciri Institusi, Inti Program Studi, Pendukung
            $table->string('semester'); // I, II, III, IV, V, VI, VII, VIII
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
        Schema::dropIfExists('matakuliahs');
    }
};
