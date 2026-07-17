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
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');
            $table->enum('status_kerja', ['Bekerja', 'Wirausaha', 'Melanjutkan Studi', 'Belum Bekerja']);
            $table->integer('waktu_tunggu')->nullable()->comment('Waktu tunggu kerja pertama dalam bulan');
            $table->enum('kesesuaian_bidang', ['Sangat Sesuai', 'Sesuai', 'Kurang Sesuai', 'Tidak Sesuai'])->nullable();
            $table->enum('tingkat_tempat_kerja', ['Lokal', 'Nasional', 'Internasional'])->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->bigInteger('pendapatan_pertama')->nullable()->comment('Dalam Rupiah');
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
        Schema::dropIfExists('tracer_studies');
    }
};
