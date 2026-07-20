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
        Schema::table('alumnis', function (Blueprint $table) {
            if (Schema::hasColumn('alumnis', 'linkedin_url')) {
                $table->dropColumn('linkedin_url');
            }
            if (!Schema::hasColumn('alumnis', 'instagram_url')) {
                $table->string('instagram_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            if (Schema::hasColumn('alumnis', 'instagram_url')) {
                $table->dropColumn('instagram_url');
            }
            if (!Schema::hasColumn('alumnis', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable();
            }
        });
    }
};
