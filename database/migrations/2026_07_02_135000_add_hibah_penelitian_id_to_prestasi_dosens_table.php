<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prestasi_dosens', function (Blueprint $table) {
            $table->foreignId('hibah_penelitian_id')
                ->nullable()
                ->after('ts_id')
                ->constrained('hibah_penelitians')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('prestasi_dosens', function (Blueprint $table) {
            $table->dropForeign(['hibah_penelitian_id']);
            $table->dropColumn('hibah_penelitian_id');
        });
    }
};
