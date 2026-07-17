<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->foreignId('prestasi_dosen_id')
                ->nullable()
                ->after('hki_id')
                ->constrained('prestasi_dosens')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->dropForeign(['prestasi_dosen_id']);
            $table->dropColumn('prestasi_dosen_id');
        });
    }
};
