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
        Schema::table('praktisis', function (Blueprint $table) {
            $table->string('pendidikan_terakhir')->nullable()->after('nama_praktisi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('praktisis', function (Blueprint $table) {
            $table->dropColumn('pendidikan_terakhir');
        });
    }
};
