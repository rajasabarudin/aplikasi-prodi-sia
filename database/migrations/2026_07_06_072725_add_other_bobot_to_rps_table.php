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
        Schema::table('rps', function (Blueprint $table) {
            $table->integer('bobot_praktek')->nullable()->default(0)->after('bobot_project');
            $table->integer('bobot_kuis')->nullable()->default(0)->after('bobot_praktek');
            $table->integer('bobot_uts')->nullable()->default(0)->after('bobot_kuis');
            $table->integer('bobot_uas')->nullable()->default(0)->after('bobot_uts');
            $table->integer('bobot_presentasi')->nullable()->default(0)->after('bobot_uas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rps', function (Blueprint $table) {
            $table->dropColumn([
                'bobot_praktek',
                'bobot_kuis',
                'bobot_uts',
                'bobot_uas',
                'bobot_presentasi'
            ]);
        });
    }
};
