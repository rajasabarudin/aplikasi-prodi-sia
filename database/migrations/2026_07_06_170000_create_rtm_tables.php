<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rtms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->onDelete('cascade');
            $table->string('nomor_dokumen')->nullable();
            $table->string('dosen_pengampu')->nullable();
            $table->integer('semester')->nullable();
            $table->timestamps();
        });

        Schema::create('rtm_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rtm_id')->constrained('rtms')->onDelete('cascade');
            $table->integer('minggu_ke');
            $table->integer('tugas_ke');
            $table->string('bentuk_tugas')->nullable();
            $table->string('judul_tugas')->nullable();
            $table->text('sub_cpmk')->nullable();
            $table->text('obyek_garapan')->nullable();
            $table->text('metode_pengerjaan')->nullable();
            $table->text('bentuk_format_luaran')->nullable();
            $table->string('waktu_pengerjaan')->nullable();
            $table->string('waktu_pengumpulan')->nullable();
            $table->text('lain_lain')->nullable();
            $table->text('daftar_rujukan')->nullable();
            $table->timestamps();
        });

        Schema::create('rtm_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rtm_tugas_id')->constrained('rtm_tugas')->onDelete('cascade');
            $table->string('indikator')->nullable();
            $table->string('teknik_penilaian')->nullable();
            $table->string('bobot_penilaian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rtm_penilaians');
        Schema::dropIfExists('rtm_tugas');
        Schema::dropIfExists('rtms');
    }
};
