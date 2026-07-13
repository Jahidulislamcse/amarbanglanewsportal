<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDivisionTable extends Migration
{
    public function up()
    {
        Schema::create('admin_division', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id');
            $table->integer('division_id');
            $table->unique(['admin_id', 'division_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_division');
    }
}

