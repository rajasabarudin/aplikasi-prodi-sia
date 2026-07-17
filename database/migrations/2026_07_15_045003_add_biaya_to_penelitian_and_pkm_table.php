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
        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->bigInteger('biaya')->default(0)->after('jenis_penelitian');
        });

        Schema::table('pkm_dosens', function (Blueprint $table) {
            $table->bigInteger('biaya')->default(0)->after('jenis_pkm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->dropColumn('biaya');
        });

        Schema::table('pkm_dosens', function (Blueprint $table) {
            $table->dropColumn('biaya');
        });
    }
};
