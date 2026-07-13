<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('reader_type', ['free', 'executive', 'vip'])->default('free')->after('is_reader');

            $table->decimal('team_gen_1', 5, 2)->default(0)->after('reader_type'); 
            $table->decimal('team_gen_2', 5, 2)->default(0)->after('team_gen_1');
            $table->decimal('team_gen_3', 5, 2)->default(0)->after('team_gen_2');
            $table->decimal('team_gen_4', 5, 2)->default(0)->after('team_gen_3');
            $table->decimal('team_gen_5', 5, 2)->default(0)->after('team_gen_4');

            $table->boolean('promotion_eligible')->default(false)->after('team_gen_5');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'reader_type',
                'team_gen_1',
                'team_gen_2',
                'team_gen_3',
                'team_gen_4',
                'team_gen_5',
                'promotion_eligible',
            ]);
        });
    }
};