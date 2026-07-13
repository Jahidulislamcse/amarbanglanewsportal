<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safely drop foreign keys if they exist
        try {
            Schema::table('salaries', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('advance_salaries', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
            });
        } catch (\Exception $e) {}

        // Drop tables if they exist
        Schema::dropIfExists('employees');
        Schema::dropIfExists('designations');

        // Add columns to admins table
        if (!Schema::hasColumn('admins', 'salary')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->decimal('salary', 10, 2)->default(0.00);
            });
        }
        if (!Schema::hasColumn('admins', 'account_details')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->text('account_details')->nullable();
            });
        }

        // Recreate employee_id column in salaries as int(11) to match admins.id
        try {
            Schema::table('salaries', function (Blueprint $table) {
                $table->dropColumn('employee_id');
            });
        } catch (\Exception $e) {}
        
        Schema::table('salaries', function (Blueprint $table) {
            $table->integer('employee_id');
        });

        // Recreate employee_id column in advance_salaries as int(11) to match admins.id
        try {
            Schema::table('advance_salaries', function (Blueprint $table) {
                $table->dropColumn('employee_id');
            });
        } catch (\Exception $e) {}

        Schema::table('advance_salaries', function (Blueprint $table) {
            $table->integer('employee_id');
        });

        // Truncate tables to remove any orphaned rows and prevent integrity constraint failures
        DB::table('salaries')->truncate();
        DB::table('advance_salaries')->truncate();

        // Add foreign keys
        Schema::table('salaries', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')
                ->on('admins')
                ->cascadeOnDelete();
        });

        Schema::table('advance_salaries', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')
                ->on('admins')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse operations
    }
};
