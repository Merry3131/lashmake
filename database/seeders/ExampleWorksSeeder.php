<?php

namespace Database\Seeders;

use App\Models\Works;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExampleWorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $works = [
            [
                'specialist_id' => 1,
                'service_id' => 1,
                'description' => 'описани 1'
            ],
            [
                'specialist_id' => 2,
                'service_id' => 2,
                'description' => 'описани 2'
            ],
            [
                'specialist_id' => 3,
                'service_id' => 3,
                'description' => 'описани 3'
            ],
            [
                'specialist_id' => 4,
                'service_id' => 4,
                'description' => 'описани 4'
            ],
        ];
        foreach ($works as $work) {
            Works::create($work);
        }
    }
}
