<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Nilai CPMK Mahasiswa (untuk CPL Attainment)
        Schema::create('obe_nilai_cpmk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('cpmk_id')->constrained('cpmks')->onDelete('cascade');
            $table->decimal('nilai', 5, 2)->default(0.00); // Nilai 0.00 - 100.00
            $table->timestamps();
        });

        // 2. Tabel CQI (Continuous Quality Improvement) Logs
        Schema::create('obe_cqi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->onDelete('cascade');
            $table->string('semester', 20)->comment('Contoh: 2024/2025 Ganjil');
            $table->foreignId('cpl_id')->nullable()->constrained('cpls')->onDelete('set null');
            $table->text('analisis_masalah');
            $table->text('rencana_perbaikan');
            $table->enum('status', ['draft', 'implemented'])->default('draft');
            $table->timestamps();
        });

        // 3. Tabel Survei Stakeholder (Pengguna Lulusan & Alumni)
        Schema::create('obe_stakeholder_surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('aspek_penilaian', 150)->comment('Contoh: Etika, Keahlian IT, Kerjasama, dll');
            $table->decimal('nilai_sangat_baik', 5, 2)->default(0.00); // %
            $table->decimal('nilai_baik', 5, 2)->default(0.00);        // %
            $table->decimal('nilai_cukup', 5, 2)->default(0.00);       // %
            $table->decimal('nilai_kurang', 5, 2)->default(0.00);      // %
            $table->integer('responden_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obe_stakeholder_surveys');
        Schema::dropIfExists('obe_cqi_logs');
        Schema::dropIfExists('obe_nilai_cpmk');
    }
};
