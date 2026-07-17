<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tugas_mahasiswas', function (Blueprint $table) {
            $table->text('anggota_kelompok')->nullable()->after('link_dokumen');
        });
    }

    public function down()
    {
        Schema::table('tugas_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('anggota_kelompok');
        });
    }
};
