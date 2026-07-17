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
    public function up()
    {
        Schema::create('kegiatan_tendiks', function (Blueprint $table) {
            $table->id();
            $table->string('nip_nik');
            $table->string('nama_tendik');
            $table->string('nama_kegiatan');
            $table->integer('tahun');
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->string('penyelenggara');
            $table->string('jenis')->comment('Internal atau Eksternal');
            $table->foreignId('kegiatan_prodi_id')->nullable()->constrained('kegiatans')->nullOnDelete();
            $table->string('link_dokumen')->nullable();
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
        Schema::dropIfExists('kegiatan_tendiks');
    }
};
