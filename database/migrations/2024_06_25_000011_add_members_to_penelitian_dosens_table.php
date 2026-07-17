<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->string('nim_mhs')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->text('anggota_mitra')->nullable();
        });
    }

    public function down()
    {
        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->dropColumn(['nim_mhs', 'nama_mahasiswa', 'anggota_mitra']);
        });
    }
};
