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
        Schema::create('beasiswa_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->enum('jenis_beasiswa', ['internal', 'eksternal']);
            $table->string('kategori_beasiswa');
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
        Schema::dropIfExists('beasiswa_mahasiswas');
    }
};
