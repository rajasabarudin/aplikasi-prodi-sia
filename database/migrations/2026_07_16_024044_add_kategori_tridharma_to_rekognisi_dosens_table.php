<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->enum('kategori_tridharma', ['penelitian', 'pengabdian_masyarakat', 'pendidikan'])
                  ->nullable()
                  ->after('is_keanggotaan');
        });
    }

    public function down(): void
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->dropColumn('kategori_tridharma');
        });
    }
};
