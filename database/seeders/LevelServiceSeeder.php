<?php

namespace Database\Seeders;

use App\Models\LevelService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelService = [
            ['service_id' => 1, 'level_id' => 2, 'price' => 2600, 'duration' => 120],
        ];
        foreach ($levelService as $item) {
            LevelService::create(['level_id' => $item['level_id'], 'service_id' => $item['service_id'], 'price' => $item['price'], 'duration' => $item['duration']]);
        }
    }
}
