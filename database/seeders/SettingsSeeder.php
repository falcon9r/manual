<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->truncate();
        Setting::query()->create([
            'key' => 'welcome-page',
            'value' => 'welcome page'
        ]);
        Setting::query()->create([
            'key' => 'settings-dark-theme',
            'value' => 'temp'
        ]);
    }
}
