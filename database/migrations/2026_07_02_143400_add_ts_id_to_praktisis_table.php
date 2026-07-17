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
            $table->unsignedBigInteger('ts_id')->after('nama_praktisi');
            
            // Add foreign key constraint
            $table->foreign('ts_id')->references('id')->on('ts')->onDelete('cascade');
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
            $table->dropForeign(['ts_id']);
            $table->dropColumn('ts_id');
        });
    }
};
