<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('experience')->nullable();

            $table->boolean('has_experience')->default(false);

            $table->string('experience_organization')->nullable();

            $table->string('experience_designation')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'experience',
                'has_experience',
                'experience_organization',
                'experience_designation'
            ]);
        });
    }
};