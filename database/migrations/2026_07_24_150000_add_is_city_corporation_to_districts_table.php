<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCityCorporationToDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts', function (Blueprint $table) {
            if (!Schema::hasColumn('districts', 'is_city_corporation')) {
                $table->tinyInteger('is_city_corporation')->default(0);
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
        Schema::table('districts', function (Blueprint $table) {
            if (Schema::hasColumn('districts', 'is_city_corporation')) {
                $table->dropColumn('is_city_corporation');
            }
        });
    }
}
