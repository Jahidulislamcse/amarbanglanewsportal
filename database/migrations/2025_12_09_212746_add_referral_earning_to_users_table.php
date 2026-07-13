<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_earning')) {
                $table->decimal('referral_earning', 15, 2)->default(0.00)->after('affilate_code');
            }
        });
    }


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referral_earning')) {
                $table->dropColumn('referral_earning');
            }
        });
    }
};