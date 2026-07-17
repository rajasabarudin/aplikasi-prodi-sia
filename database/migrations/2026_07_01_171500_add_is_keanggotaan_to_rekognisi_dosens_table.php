<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->boolean('is_keanggotaan')->default(false)->after('link_dokumen');
        });

        // Automatically mark existing records that are clearly memberships
        try {
            DB::table('rekognisi_dosens')
                ->where('nama_rekognisi', 'like', '%keanggotaan%')
                ->orWhere('nama_rekognisi', 'like', '%anggota%')
                ->orWhere('nama_rekognisi', 'like', '%pengurus%')
                ->orWhere('nama_rekognisi', 'like', '%afebsi%')
                ->orWhere('nama_rekognisi', 'like', '%fmi%')
                ->orWhere('nama_rekognisi', 'like', '%redaksi%')
                ->orWhere('nama_rekognisi', 'like', '%editor%')
                ->orWhere('nama_rekognisi', 'like', '%reviewer%')
                ->update(['is_keanggotaan' => true]);
        } catch (\Exception $e) {
            // Ignore if any errors
        }
    }

    public function down()
    {
        Schema::table('rekognisi_dosens', function (Blueprint $table) {
            $table->dropColumn('is_keanggotaan');
        });
    }
};
