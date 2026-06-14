<?php

namespace Database\Seeders;

use App\Models\Works;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


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
                'description' => 'описани 1',
                'image' => public_path('img/example1.jpg'),
            ],
            [
                'specialist_id' => 2,
                'service_id' => 2,
                'description' => 'описани 2',
                'image' => public_path('img/example1.jpg'),
            ],
            [
                'specialist_id' => 3,
                'service_id' => 3,
                'description' => 'описани 3',
                'image' => public_path('img/example1.jpg'),
            ],
            [
                'specialist_id' => 4,
                'service_id' => 4,
                'description' => 'описани 4',
                'image' => public_path('img/example1.jpg'),
            ],
        ];
        foreach ($works as $work) {
            $item = Works::create(['specialist_id' => $work['specialist_id'], 'service_id' => $work['service_id'], 'description' => $work['description']]);
            if (File::exists($work['image'])) {
                $item->addMedia($work['image'])
                    ->preservingOriginal()
                    ->toMediaCollection('works');
            }
        }
    }
}
