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
    public function up(): void
    {
        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->string('judul_penelitian')->nullable()->after('nama_dosen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->dropColumn('judul_penelitian');
        });
    }
};
