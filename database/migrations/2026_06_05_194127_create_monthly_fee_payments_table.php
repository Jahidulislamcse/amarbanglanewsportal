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
        Schema::create('monthly_fee_payments', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('user_id');
        
            $table->string('transaction_id')->unique();
        
            $table->decimal('amount', 10, 2);
        
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'cancelled'
            ])->default('pending');
        
            $table->json('gateway_response')->nullable();
        
            $table->timestamp('paid_at')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_fee_payments');
    }
};
