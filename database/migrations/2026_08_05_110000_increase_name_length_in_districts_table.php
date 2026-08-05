<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class IncreaseNameLengthInDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using raw SQL to modify column size to avoid dependency on doctrine/dbal
        DB::statement("ALTER TABLE districts MODIFY name VARCHAR(50) NOT NULL");
        DB::statement("ALTER TABLE districts MODIFY bn_name VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE districts MODIFY name VARCHAR(25) NOT NULL");
        DB::statement("ALTER TABLE districts MODIFY bn_name VARCHAR(25) NOT NULL");
    }
}
