<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();

            $table->decimal('free_reader_rate', 10, 2)->default(0);
            $table->decimal('executive_reader_rate', 10, 2)->default(0);
            $table->decimal('vip_reader_rate', 10, 2)->default(0);
            
            $table->decimal('reader_view_rate', 10, 2)->default(0);

            $table->decimal('reporter_view_rate', 10, 2)->default(0);

            $table->decimal('referral_commission', 10, 2)->default(0);
            $table->decimal('referral_view_commission', 10, 2)->default(0);

            $table->decimal('team_gen_1_rate', 10, 2)->default(0);
            $table->decimal('team_gen_2_rate', 10, 2)->default(0);
            $table->decimal('team_gen_3_rate', 10, 2)->default(0);
            $table->decimal('team_gen_4_rate', 10, 2)->default(0);
            $table->decimal('team_gen_5_rate', 10, 2)->default(0);

            $table->decimal('executive_package_price', 10, 2)->default(0);
            $table->decimal('vip_package_price', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};