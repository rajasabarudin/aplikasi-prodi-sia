<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ts', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_sekarang', 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ts');
    }
};
