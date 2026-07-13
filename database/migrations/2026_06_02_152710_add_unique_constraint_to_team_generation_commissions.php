<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_generation_commissions', function (Blueprint $table) {
            $table->unique(
                ['receiver_user_id', 'source_user_id', 'upgrade_request_id', 'generation'],
                'team_gen_commission_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('team_generation_commissions', function (Blueprint $table) {
            $table->dropUnique('team_gen_commission_unique');
        });
    }
};