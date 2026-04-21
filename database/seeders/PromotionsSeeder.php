<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = [
            [
                'service_id' => 1, // Объём 2д
                'specialist_id' => 2, // Надежда (Top-master)
                'title' => 'Весеннее преображение: -15% у Надежды',
                'type' => 'discount',
                'discount_percent' => 15,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
            ],
            [
                'service_id' => 2, // Ламинирование ресниц
                'specialist_id' => 4, // Дарья (Top-master)
                'title' => 'Знакомство с новым мастером Дарьей',
                'type' => 'discount',
                'discount_percent' => 20,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
            ],
            [
                'service_id' => 3, // Классика 1д
                'specialist_id' => 2, // Акция действует у всех
                'title' => 'Счастливые часы: -10% на классику',
                'type' => 'discount',
                'discount_percent' => 10,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addYear(),
            ],
        ];

        foreach ($promotions as $data) {
            Promotion::create($data);
        }
    }
}
