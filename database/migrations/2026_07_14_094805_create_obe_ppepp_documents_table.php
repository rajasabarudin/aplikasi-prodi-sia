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
        Schema::create('obe_ppepp_documents', function (Blueprint $table) {
            $table->id();
            $table->string('kriteria'); // C1 - C9
            $table->string('ppepp');    // P1, P2, P3, P4, P5
            $table->string('nama_dokumen');
            $table->string('file_path');
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
        Schema::dropIfExists('obe_ppepp_documents');
    }
};
