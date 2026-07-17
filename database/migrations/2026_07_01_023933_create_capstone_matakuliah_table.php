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
        Schema::create('capstone_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('capstone_mahasiswa_id');
            $table->unsignedBigInteger('matakuliah_id');
            $table->timestamps();

            $table->foreign('capstone_mahasiswa_id')->references('id')->on('capstone_mahasiswas')->onDelete('cascade');
            $table->foreign('matakuliah_id')->references('id')->on('matakuliahs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capstone_matakuliah');
    }
};
