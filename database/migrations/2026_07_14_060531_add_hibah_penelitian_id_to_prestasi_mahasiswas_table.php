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
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->foreignId('hibah_penelitian_id')->nullable()->constrained('hibah_penelitians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['hibah_penelitian_id']);
            $table->dropColumn('hibah_penelitian_id');
        });
    }
};
