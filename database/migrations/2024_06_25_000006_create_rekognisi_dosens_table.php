<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rekognisi_dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dosen', 20);
            $table->string('nama_dosen', 100);
            $table->string('nama_rekognisi', 200);
            $table->string('tahun', 10);
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->enum('level', ['lokal', 'nasional', 'internasional']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekognisi_dosens');
    }
};
