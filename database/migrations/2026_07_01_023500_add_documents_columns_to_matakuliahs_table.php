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
            $table->string('link_modul')->nullable()->after('semester');
            $table->string('link_rps')->nullable()->after('link_modul');
            $table->string('link_rtm')->nullable()->after('link_rps');
            $table->string('dokumen_tambahan')->nullable()->after('link_rtm');
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
            $table->dropColumn(['link_modul', 'link_rps', 'link_rtm', 'dokumen_tambahan']);
        });
    }
};
