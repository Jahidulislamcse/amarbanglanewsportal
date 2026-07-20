<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToUsersAndPostsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add indexes to users table to optimize filters and sorting
        Schema::table('users', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('users');

            if (!array_key_exists('users_is_reader_idx', $indexes)) {
                $table->index('is_reader', 'users_is_reader_idx');
            }
            if (!array_key_exists('users_created_at_idx', $indexes)) {
                $table->index('created_at', 'users_created_at_idx');
            }
            if (!array_key_exists('users_division_id_idx', $indexes)) {
                $table->index('division_id', 'users_division_id_idx');
            }
            if (!array_key_exists('users_district_id_idx', $indexes)) {
                $table->index('district_id', 'users_district_id_idx');
            }
            if (!array_key_exists('users_thana_id_idx', $indexes)) {
                $table->index('thana_id', 'users_thana_id_idx');
            }
        });

        // Add indexes to posts table to optimize subquery counts
        Schema::table('posts', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('posts');

            if (!array_key_exists('posts_user_id_idx', $indexes)) {
                $table->index('user_id', 'posts_user_id_idx');
            }
            if (!array_key_exists('posts_user_pending_idx', $indexes)) {
                $table->index(['user_id', 'is_pending'], 'posts_user_pending_idx');
            }
            if (!array_key_exists('posts_user_created_idx', $indexes)) {
                $table->index(['user_id', 'created_at'], 'posts_user_created_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('users');

            if (array_key_exists('users_is_reader_idx', $indexes)) {
                $table->dropIndex('users_is_reader_idx');
            }
            if (array_key_exists('users_created_at_idx', $indexes)) {
                $table->dropIndex('users_created_at_idx');
            }
            if (array_key_exists('users_division_id_idx', $indexes)) {
                $table->dropIndex('users_division_id_idx');
            }
            if (array_key_exists('users_district_id_idx', $indexes)) {
                $table->dropIndex('users_district_id_idx');
            }
            if (array_key_exists('users_thana_id_idx', $indexes)) {
                $table->dropIndex('users_thana_id_idx');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('posts');

            if (array_key_exists('posts_user_id_idx', $indexes)) {
                $table->dropIndex('posts_user_id_idx');
            }
            if (array_key_exists('posts_user_pending_idx', $indexes)) {
                $table->dropIndex('posts_user_pending_idx');
            }
            if (array_key_exists('posts_user_created_idx', $indexes)) {
                $table->dropIndex('posts_user_created_idx');
            }
        });
    }
}
