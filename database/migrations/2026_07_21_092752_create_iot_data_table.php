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
        Schema::create('iot_data', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->float('kelembaban_tanah_persen')->nullable();
            $table->float('suhu_tanah_celcius')->nullable();
            $table->float('suhu_udara_celcius')->nullable();
            $table->float('kelembaban_udara_persen')->nullable();
            $table->dateTime('waktu')->unique();
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
        Schema::dropIfExists('iot_data');
    }
};
