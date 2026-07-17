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
    public function up(): void
    {
        Schema::create('nilai_mentah_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->string('tahun_ajaran')->nullable();
            $table->float('nilai_kehadiran')->nullable()->default(0);
            $table->float('nilai_tugas')->nullable()->default(0);
            $table->float('nilai_project')->nullable()->default(0);
            $table->float('nilai_praktek')->nullable()->default(0);
            $table->float('nilai_kuis')->nullable()->default(0);
            $table->float('nilai_uts')->nullable()->default(0);
            $table->float('nilai_uas')->nullable()->default(0);
            $table->float('nilai_presentasi')->nullable()->default(0);
            $table->float('nilai_akhir')->nullable()->default(0);
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
        Schema::dropIfExists('nilai_mentah_mahasiswas');
    }
};
