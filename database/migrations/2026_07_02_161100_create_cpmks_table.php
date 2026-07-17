<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpmks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cpmk')->unique(); // e.g. CPMK.04.1
            $table->text('deskripsi_cpmk');
            $table->unsignedBigInteger('cpl_id');
            $table->string('kode_matakuliah');
            $table->timestamps();

            $table->foreign('cpl_id')->references('id')->on('cpls')->onDelete('cascade');
            $table->foreign('kode_matakuliah')->references('kode_matakuliah')->on('matakuliahs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpmks');
    }
};
