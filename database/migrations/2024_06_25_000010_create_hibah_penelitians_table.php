<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hibah_penelitians', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_hibah'); // internal / eksternal
            $table->string('kode_dosen');
            $table->string('nama_dosen');
            $table->string('nim_mhs')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->string('skema_hibah');
            $table->string('tema_hibah');
            $table->string('topik_hibah');
            $table->string('judul');
            $table->decimal('biaya', 15, 2);
            $table->string('link_proposal')->nullable();
            $table->string('link_st')->nullable();
            $table->string('link_spk')->nullable();
            $table->string('link_luaran')->nullable();
            $table->string('link_laporan')->nullable();
            $table->string('link_persentasi')->nullable();
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->string('pemberi_hibah');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hibah_penelitians');
    }
};
