<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_penelitian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->onDelete('cascade');
            $table->foreignId('penelitian_dosen_id')->constrained('penelitian_dosens')->onDelete('cascade');
            $table->string('bentuk_integrasi')->nullable()->comment('Bentuk integrasi dalam pembelajaran, misal: studi kasus, bahan ajar, dll');
            $table->timestamps();
        });

        Schema::create('rps_pkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->onDelete('cascade');
            $table->foreignId('pkm_dosen_id')->constrained('pkm_dosens')->onDelete('cascade');
            $table->string('bentuk_integrasi')->nullable()->comment('Bentuk integrasi dalam pembelajaran, misal: studi kasus, bahan ajar, dll');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_pkm');
        Schema::dropIfExists('rps_penelitian');
    }
};
