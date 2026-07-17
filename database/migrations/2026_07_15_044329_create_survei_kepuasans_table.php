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
        Schema::create('survei_kepuasans', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_akademik');
            $table->string('jenis_survei');
            $table->float('sangat_baik')->default(0);
            $table->float('baik')->default(0);
            $table->float('cukup')->default(0);
            $table->float('kurang')->default(0);
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
            
            $table->unique(['tahun_akademik', 'jenis_survei']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_kepuasans');
    }
};
