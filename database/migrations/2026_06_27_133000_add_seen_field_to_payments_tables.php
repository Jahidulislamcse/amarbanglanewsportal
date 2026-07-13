<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'monthly_fee_payments',
            'package_upgrade_payments',
            'product_payments',
            'book_purchases',
            'course_purchases'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->boolean('seen')->default(0)->after('status');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'monthly_fee_payments',
            'package_upgrade_payments',
            'product_payments',
            'book_purchases',
            'course_purchases'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'seen')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('seen');
                });
            }
        }
    }
};
