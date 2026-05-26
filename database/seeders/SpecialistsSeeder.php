<?php

namespace Database\Seeders;

use App\Enums\LevelSpecialistEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alenaId = DB::table('users')->where('email', 'habibullina_alena@gmail.com')->value('id');
        $nadegdaId = DB::table('users')->where('email', 'nadegda@mail.ru')->value('id');
        $nadegdaRId = DB::table('users')->where('email', 'nadejda_r@gmail.com')->value('id');
        $dariaId = DB::table('users')->where('email', 'daria_d@mail.ru')->value('id');

        // Проверим, что мы всех нашли (если кто-то null - выкинем ошибку сами)
        if (!$alenaId || !$nadegdaId || !$nadegdaRId || !$dariaId) {
            throw new \Exception("Один из пользователей не найден в базе по email! Проверь UserSeeder.");
        }
        $specialists = [
          [
              'user_id' => $alenaId,
              'level_id' => 1,
              'experience' => '8 лет',
              'bio' => 'Руководитель студии и ведущий специалист по наращиванию ресниц'

          ],
            [
                'user_id' => $nadegdaId,
                'level_id' => 2,
                'experience' => '6 лет',
                'bio' => 'Ведущий специалист по наращиванию ресниц'

            ],
            [
                'user_id' => $nadegdaRId,
                'level_id' => 3,
                'experience' => '4 года',
                'bio' => 'Мастер по ламинированию ресниц и оформлению бровей'

            ],
            [
                'user_id' => $dariaId,
                'level_id' => 2,
                'experience' => '5 лет',
                'bio' => 'Ведущий специалист по наращиванию ресниц'

            ]
        ];

        DB::table('specialists')->insert($specialists);
    }
}
