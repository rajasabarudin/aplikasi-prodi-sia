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
        Schema::table('matakuliahs', function (Blueprint $table) {
            $table->integer('sks_t')->default(0)->after('sks');
            $table->integer('sks_pa')->default(0)->after('sks_t');
            $table->integer('sks_pu')->default(0)->after('sks_pa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matakuliahs', function (Blueprint $table) {
            $table->dropColumn(['sks_t', 'sks_pa', 'sks_pu']);
        });
    }
};
