<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpls', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cpl')->unique(); // e.g. CPL01, CPL04
            $table->text('deskripsi_cpl');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpls');
    }
};
