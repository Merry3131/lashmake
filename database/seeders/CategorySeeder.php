<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Works;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

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
                'description' => 'От классики до мега-объема. Подберем идеальный изгиб под твой разрез глаз.',
                'image' => public_path('img/categories/lash1.jpg'),

            ],
            [
                'slug' => 'lamination',
                'display_name' => 'Ламинирование ресниц',
                'description' => 'Процедура, которая сделает ваши ресницы визуально длиннее и гуще.',
                'image' => public_path('img/categories/lash2.jpg'),
            ],
            [
                'slug' => 'brows',
                'display_name' => 'Оформление бровей',
                'description' => 'Идеальная форма и цвет, подходящие именно вам. Коррекция и окрашивание.',
                'image' => public_path('img/categories/brows.jpg'),
            ],
            [
                'slug' => 'makeup',
                'display_name' => 'Перманентный макияж',
                'description' => 'Тонкое подчеркивание вашей природной красоты.',
                'image' => public_path('img/categories/makeup.jpg'),
            ],
            [
                'slug' => 'cosmetologia',
                'display_name' => 'Косметология',
                'description' => 'Свежесть, тонус и природный баланс вашей кожи. Персональные программы преображения.',
                'image' => public_path('img/categories/cosmet.jpg'),
            ],
            [
                'slug' => 'sale',
                'display_name' => 'Акции',
                'description' => 'Идеальный повод уделить время себе с заботой о бюджете.',
                'image' => public_path('img/categories/sale.png'),
            ],
        ];

        foreach ($categories as $category) {
            $item = Category::create(['slug' => $category['slug'], 'display_name' => $category['display_name'], 'description' => $category['description']]);
            if (File::exists($category['image'])) {
                $item->addMedia($category['image'])
                    ->preservingOriginal()
                    ->toMediaCollection('categories');
            }
        }
    }
}
