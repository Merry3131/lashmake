<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'lashes',
                'display_name' => 'Наращивание ресниц',
                'description' => 'От классики до мега-объема. Подберем идеальный изгиб под твой разрез глаз.'
            ],
            [
                'slug' => 'lamination',
                'display_name' => 'Ламинирование ресниц',
                'description' => 'Процедура, которая сделает ваши ресницы визуально длиннее и гуще.'
            ],
            [
                'slug' => 'brows',
                'display_name' => 'Оформление бровей',
                'description' => 'Идеальная форма и цвет, подходящие именно вам. Коррекция и окрашивание.'
            ],
            [
                'slug' => 'promotion',
                'display_name' => 'Акции',
                'description' => 'Следите за нашими акциями, чтобы получить услугу по низкой цене.'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
