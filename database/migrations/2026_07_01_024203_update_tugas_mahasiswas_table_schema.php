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
        Schema::dropIfExists('tugas_mahasiswas');

        Schema::create('tugas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->unsignedBigInteger('matakuliah_id');
            $table->string('link_dokumen')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('matakuliah_id')->references('id')->on('matakuliahs')->onDelete('cascade');
            
            // Enforce 1 assignment per course per student
            $table->unique(['nim', 'matakuliah_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tugas_mahasiswas');
        
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
};
