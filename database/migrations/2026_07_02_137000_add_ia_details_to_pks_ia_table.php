<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pks_ia', function (Blueprint $table) {
            $table->string('no_ia_ubsi')->nullable()->after('no_pks_mitra');
            $table->string('no_ia_mitra')->nullable()->after('no_ia_ubsi');
            $table->string('judul_ia')->nullable()->after('tema_pks');
            $table->date('tgl_ia')->nullable()->after('tgl_pks');
        });
    }

    public function down()
    {
        Schema::table('pks_ia', function (Blueprint $table) {
            $table->dropColumn(['no_ia_ubsi', 'no_ia_mitra', 'judul_ia', 'tgl_ia']);
        });
    }
};
