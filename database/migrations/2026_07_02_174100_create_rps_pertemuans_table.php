<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_pertemuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->onDelete('cascade');
            $table->string('minggu_ke'); // e.g. "1", "3-4"
            $table->text('sub_cpmk')->nullable();
            $table->text('bahan_kajian')->nullable();
            $table->text('metode_pembelajaran')->nullable();
            $table->text('pengalaman_belajar')->nullable();
            $table->text('indikator_penilaian')->nullable();
            $table->integer('bobot_penilaian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_pertemuans');
    }
};
