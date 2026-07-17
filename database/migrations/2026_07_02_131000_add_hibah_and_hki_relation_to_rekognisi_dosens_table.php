<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->foreignId('hibah_penelitian_id')
                ->nullable()
                ->after('penelitian_dosen_id')
                ->constrained('hibah_penelitians')
                ->onDelete('cascade');

            $table->foreignId('hki_id')
                ->nullable()
                ->after('hibah_penelitian_id')
                ->constrained('hkis')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->dropForeign(['hki_id']);
            $table->dropColumn('hki_id');

            $table->dropForeign(['hibah_penelitian_id']);
            $table->dropColumn('hibah_penelitian_id');
        });
    }
};
