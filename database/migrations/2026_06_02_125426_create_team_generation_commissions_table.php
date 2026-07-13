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
        Schema::create('team_generation_commissions', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('receiver_user_id');
            $table->foreignId('source_user_id');
        
            $table->foreignId('upgrade_request_id')->nullable();
        
            $table->unsignedTinyInteger('generation');
        
            $table->string('package')->nullable();
        
            $table->decimal('upgrade_amount', 12, 2)->default(0);
        
            $table->decimal('rate', 8, 2)->default(0);
        
            $table->decimal('commission', 12, 2)->default(0);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_generation_commissions');
    }
};
