<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_bahan_kajians', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matakuliah');
            $table->integer('urutan'); // 1-16 (minggu ke-)
            $table->string('topik');
            $table->text('sub_topik')->nullable();
            $table->timestamps();

            $table->foreign('kode_matakuliah')->references('kode_matakuliah')->on('matakuliahs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_bahan_kajians');
    }
};
