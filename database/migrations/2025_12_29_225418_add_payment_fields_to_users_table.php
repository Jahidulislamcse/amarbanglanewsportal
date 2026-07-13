<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('approved_date')->nullable()->after('views');
            $table->dateTime('next_payment_date')->nullable()->after('approved_date');
            $table->enum('payment_status', ['upcoming', 'due', 'paid'])
                  ->default('upcoming')
                  ->after('next_payment_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'approved_date',
                'next_payment_date',
                'payment_status',
            ]);
        });
    }
};
