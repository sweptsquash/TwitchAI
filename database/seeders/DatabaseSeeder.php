<?php

namespace Database\Seeders;

use Domain\Config\Models\Config;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Config::create(['key' => 'open_ai_key']);
        Config::create(['key' => 'open_ai_org']);
        Config::create(['key' => 'twitch_name']);
    }
}
