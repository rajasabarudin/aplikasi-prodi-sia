<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organisasi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_organisasi');
            $table->string('jabatan'); // Ketua, Wakil Ketua, Sekretaris, Bendahara, Anggota, dll.
            $table->string('periode'); // Contoh: 2023-2024 atau 2023
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->string('link_sk')->nullable(); // Link SK Kepengurusan (Opsional)
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisasi_mahasiswas');
    }
};
