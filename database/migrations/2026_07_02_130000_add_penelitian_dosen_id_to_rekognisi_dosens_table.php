<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->foreignId('penelitian_dosen_id')
                ->nullable()
                ->after('ts_id')
                ->constrained('penelitian_dosens')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->dropForeign(['penelitian_dosen_id']);
            $table->dropColumn('penelitian_dosen_id');
        });
    }
};
