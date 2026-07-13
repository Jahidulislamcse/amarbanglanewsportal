<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\WorldCup2026Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WorldCup2026Seeder::class,
        ]);
    }
}