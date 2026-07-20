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
        $usersIndexes = [
            ['column' => 'is_reader', 'name' => 'users_is_reader_idx'],
            ['column' => 'created_at', 'name' => 'users_created_at_idx'],
            ['column' => 'division_id', 'name' => 'users_division_id_idx'],
            ['column' => 'district_id', 'name' => 'users_district_id_idx'],
            ['column' => 'thana_id', 'name' => 'users_thana_id_idx']
        ];

        foreach ($usersIndexes as $idx) {
            try {
                Schema::table('users', function (Blueprint $table) use ($idx) {
                    $table->index($idx['column'], $idx['name']);
                });
            } catch (\Exception $e) {
                // Skip if index already exists or column cannot be indexed
            }
        }

        // Add indexes to posts table to optimize subquery counts
        $postsIndexes = [
            ['column' => 'user_id', 'name' => 'posts_user_id_idx'],
            ['column' => ['user_id', 'is_pending'], 'name' => 'posts_user_pending_idx'],
            ['column' => ['user_id', 'created_at'], 'name' => 'posts_user_created_idx']
        ];

        foreach ($postsIndexes as $idx) {
            try {
                Schema::table('posts', function (Blueprint $table) use ($idx) {
                    $table->index($idx['column'], $idx['name']);
                });
            } catch (\Exception $e) {
                // Skip if index already exists or column cannot be indexed
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $usersIndexes = [
            'users_is_reader_idx',
            'users_created_at_idx',
            'users_division_id_idx',
            'users_district_id_idx',
            'users_thana_id_idx'
        ];

        foreach ($usersIndexes as $name) {
            try {
                Schema::table('users', function (Blueprint $table) use ($name) {
                    $table->dropIndex($name);
                });
            } catch (\Exception $e) {
                // Skip if index does not exist
            }
        }

        $postsIndexes = [
            'posts_user_id_idx',
            'posts_user_pending_idx',
            'posts_user_created_idx'
        ];

        foreach ($postsIndexes as $name) {
            try {
                Schema::table('posts', function (Blueprint $table) use ($name) {
                    $table->dropIndex($name);
                });
            } catch (\Exception $e) {
                // Skip if index does not exist
            }
        }
    }
}
