<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configData = [
            ['key' => 'song_prefix', 'value' => 'SNG'],
            ['key' => 'category_prefix', 'value' => 'CAT'],
            ['key' => 'playlist_prefix', 'value' => 'PLAY'],
            ['key' => 'sub_category_prefix', 'value' => 'SCAT'],
            ['key' => 'show_export', 'value' => '1'],
            ['key' => 'song_delete', 'value' => '1'],
            ['key' => 'category_delete', 'value' => '1'],
            ['key' => 'sub_category_delete', 'value' => '1'],
            ['key' => 'playlist_delete', 'value' => '1'],
            ['key' => 'song_create', 'value' => '1'],
            ['key' => 'category_create', 'value' => '1'],
            ['key' => 'sub_category_create', 'value' => '1'],
            ['key' => 'playlist_create', 'value' => '1'],
        ];

        // Loop through each configuration item and create or update it by key
        foreach ($configData as $config) {
            Configuration::updateOrCreate(
                ['key' => $config['key']],  // Search by key
                ['value' => $config['value']]  // Update or create with new value
            );
        }
    }
}
