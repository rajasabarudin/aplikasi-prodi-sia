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
        Schema::table('prestasi_dosens', function (Blueprint $table) {
            $table->string('prestasi_diraih')->nullable()->after('level_prestasi');
            $table->string('link_dokumen')->nullable()->after('prestasi_diraih');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestasi_dosens', function (Blueprint $table) {
            $table->dropColumn(['prestasi_diraih', 'link_dokumen']);
        });
    }
};
