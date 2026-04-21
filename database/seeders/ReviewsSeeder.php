<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'appointment_id' => 1,
                'user_id' => 1,
                'specialist_id' => 1,
                'rating' => 4,
                'comment' => 'Отличная работа!!'
            ],
            [
                'appointment_id' => 1,
                'user_id' => 2,
                'specialist_id' => 3,
                'rating' => 4,
                'comment' => 'Хорошая работа!!'
            ],
            [
                'appointment_id' => 1,
                'user_id' => 3,
                'specialist_id' => 2,
                'rating' => 5,
                'comment' => 'Спасибо!! Все понравилось!!'
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
