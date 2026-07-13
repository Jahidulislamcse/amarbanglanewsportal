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
       Schema::create('course_purchases', function (Blueprint $table) {
            $table->id();
        
            // MUST match users.id (INT UNSIGNED)
            $table->unsignedInteger('user_id');
        
            // MUST match courses.id (BIGINT UNSIGNED)
            $table->unsignedBigInteger('course_id');
        
            $table->string('phone_number');
            $table->string('operator');
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->timestamps();
        
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_purchases');
    }
};
