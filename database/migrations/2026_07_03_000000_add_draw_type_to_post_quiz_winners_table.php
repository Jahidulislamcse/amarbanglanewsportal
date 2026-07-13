<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_quiz_winners', function (Blueprint $table) {
            $table->string('draw_type')->default('daily')->after('position');
            $table->date('period_start')->nullable()->after('draw_type');
            $table->date('period_end')->nullable()->after('period_start');

            $table->index(['draw_type', 'period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::table('post_quiz_winners', function (Blueprint $table) {
            $table->dropIndex(['draw_type', 'period_start', 'period_end']);
            $table->dropColumn(['draw_type', 'period_start', 'period_end']);
        });
    }
};
