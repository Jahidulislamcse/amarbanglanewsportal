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
        \DB::table('users')->whereNotNull('referred_by')->update(['referral_commission_paid' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('users')->whereNotNull('referred_by')->update(['referral_commission_paid' => false]);
    }
};
