<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->integer('recent_news_limit')->default(10);
            $table->integer('popular_news_limit')->default(10);
        });
    }

    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('recent_news_limit');
            $table->dropColumn('popular_news_limit');
        });
    }
};
