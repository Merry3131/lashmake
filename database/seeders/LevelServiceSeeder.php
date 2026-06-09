<?php

namespace Database\Seeders;

use App\Models\LevelService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('level_service')->truncate();

        $levelServices = [
            // ==========================================
            // УСЛУГА 1: Наращивание ресниц 2D
            // ==========================================
            // Мастер (level_id = 3): делает долго, берет меньше
            ['service_id' => 1, 'level_id' => 3, 'price' => 2000.00, 'duration' => 150], // 2.5 часа
            // Топ-мастер (level_id = 2): делает быстрее, берет среднее
            ['service_id' => 1, 'level_id' => 2, 'price' => 2300.00, 'duration' => 120], // 2 часа
            // Ведущий специалист (level_id = 1): делает максимально быстро и качественно, самый дорогой прайс
            ['service_id' => 1, 'level_id' => 1, 'price' => 2600.00, 'duration' => 105], // 1 час 45 мин

            // ==========================================
            // УСЛУГА 2: Наращивание ресниц 3D
            // ==========================================
            ['service_id' => 2, 'level_id' => 3, 'price' => 2300.00, 'duration' => 165], // 2 часа 45 мин
            ['service_id' => 2, 'level_id' => 2, 'price' => 2600.00, 'duration' => 135], // 2 часа 15 мин
            ['service_id' => 2, 'level_id' => 1, 'price' => 3000.00, 'duration' => 120], // 2 часа

            // ==========================================
            // УСЛУГА 3: Экспресс-наращивание ресниц
            // ==========================================
            ['service_id' => 3, 'level_id' => 3, 'price' => 1500.00, 'duration' => 90],  // 1.5 часа
            ['service_id' => 3, 'level_id' => 2, 'price' => 1800.00, 'duration' => 75],  // 1 час 15 мин
            ['service_id' => 3, 'level_id' => 1, 'price' => 2100.00, 'duration' => 60],  // 1 час

            // ==========================================
            // УСЛУГА 4: Коррекция нарощенных ресниц (2D/3D)
            // ==========================================
            ['service_id' => 4, 'level_id' => 3, 'price' => 1400.00, 'duration' => 90],  // 1.5 часа
            ['service_id' => 4, 'level_id' => 2, 'price' => 1700.00, 'duration' => 75],  // 1 час 15 мин
            ['service_id' => 4, 'level_id' => 1, 'price' => 2000.00, 'duration' => 60],  // 1 час

            // ==========================================
            // УСЛУГА 5: Архитектура бровей + окрашивание + коррекция
            // ==========================================
            // Время на брови у всех одинаковое (45 минут), но цена зависит от опыта
            ['service_id' => 5, 'level_id' => 3, 'price' => 900.00,  'duration' => 45],
            ['service_id' => 5, 'level_id' => 2, 'price' => 1100.00, 'duration' => 45],
            ['service_id' => 5, 'level_id' => 1, 'price' => 1300.00, 'duration' => 45],

            // ==========================================
            // УСЛУГА 6: Ламинирование ресниц
            // ==========================================
            // На ламинирование закладываем по 60 минут
            ['service_id' => 6, 'level_id' => 3, 'price' => 1600.00, 'duration' => 60],
            ['service_id' => 6, 'level_id' => 2, 'price' => 1900.00, 'duration' => 60],
            ['service_id' => 6, 'level_id' => 1, 'price' => 2200.00, 'duration' => 60],
        ];

        foreach ($levelServices as $item) {
            LevelService::create([
                'service_id' => $item['service_id'],
                'level_id'   => $item['level_id'],
                'price'      => $item['price'],
                'duration'   => $item['duration'],
            ]);
        }
    }
}
