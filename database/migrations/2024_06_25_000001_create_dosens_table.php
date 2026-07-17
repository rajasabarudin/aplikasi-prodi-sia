<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dosen', 20)->unique();
            $table->string('nama_dosen', 100);
            $table->string('homebase_dosen', 100)->nullable();
            $table->string('nidn', 20)->nullable()->unique();
            $table->string('nuptk', 20)->nullable()->unique();
            $table->string('nip', 30)->nullable()->unique();
            $table->string('pendidikan', 50)->nullable();
            $table->string('gelar', 100)->nullable();
            $table->string('jfa', 100)->nullable();
            $table->string('kepangkatan', 100)->nullable();
            $table->string('keterangan_serdos', 100)->nullable();
            $table->string('jenjang', 50)->nullable();
            $table->string('kondisi_sisfo', 50)->nullable();
            $table->string('kondisi_pddikti', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosens');
    }
};
