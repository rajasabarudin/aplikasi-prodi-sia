<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('p_k_m_dosens');
    }

    public function down()
    {
        Schema::create('p_k_m_dosens', function ($table) {
            $table->id();
            $table->string('personil_dosen');
            $table->string('materi_pkm');
            $table->string('alamat_pkm');
            $table->string('peserta');
            $table->decimal('jumlah_dana', 15, 2);
            $table->decimal('biaya_disetujui', 15, 2);
            $table->string('sumber_iptek');
            $table->string('personil_pm');
            $table->string('link_dokumen');
            $table->timestamps();
        });
    }
};
