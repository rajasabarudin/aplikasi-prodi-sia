<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('silabus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->onDelete('cascade');
            $table->string('kode_dokumen')->nullable();
            $table->text('cpmk')->nullable();
            $table->text('sub_cpmk')->nullable();
            $table->timestamps();
        });

        Schema::create('silabus_materis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('silabus_id')->constrained('silabus')->onDelete('cascade');
            $table->integer('pertemuan');
            $table->text('materi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('silabus_materis');
        Schema::dropIfExists('silabus');
    }
};
