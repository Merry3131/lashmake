<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SpecialistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Получаем ID абсолютно всех мастеров и админа из UserSeeder
        $alenaId = DB::table('users')->where('email', 'habibullina_alena@gmail.com')->value('id');
        $nadegdaId = DB::table('users')->where('email', 'nadegda@mail.ru')->value('id');
        $svetlanaId = DB::table('users')->where('email', 'svetlata@gmail.com')->value('id');
        $marianaId = DB::table('users')->where('email', 'mariana@mail.ru')->value('id');
        $dariaId = DB::table('users')->where('email', 'dariad@mail.ru')->value('id');
        $nadegdaRId = DB::table('users')->where('email', 'nadegnar@mail.ru')->value('id');
        $olesiaId = DB::table('users')->where('email', 'olesia@mail.ru')->value('id');
        $alenaMId = DB::table('users')->where('email', 'alenam@mail.ru')->value('id');
        $mariasId = DB::table('users')->where('email', 'marias@mail.ru')->value('id');
        $anastasiaId = DB::table('users')->where('email', 'anastasia@mail.ru')->value('id');
        $karinaId = DB::table('users')->where('email', 'karina@mail.ru')->value('id');


        if (
            !$alenaId || !$nadegdaId || !$svetlanaId || !$marianaId ||
            !$dariaId || !$nadegdaRId || !$olesiaId || !$alenaMId ||
            !$mariasId || !$anastasiaId || !$karinaId
        ) {
            throw new \Exception("Один из пользователей не найден в базе по email! Проверь соответствие в UserSeeder.");
        }


        $specialists = [
            [
                'user_id' => $alenaId,
                'level_id' => 1,
                'experience' => '8 лет',
                'bio' => 'Руководитель студии и ведущий специалист по наращиванию ресниц',
                'image' => public_path('img/specialists/alena.jpg'),
            ],
            [
                'user_id' => $nadegdaId,
                'level_id' => 1,
                'experience' => '6 лет',
                'bio' => 'Ведущий специалист по наращиванию ресниц',
                'image' => public_path('img/specialists/nad1.jpg'),
            ],
            [
                'user_id' => $nadegdaRId,
                'level_id' => 2,
                'experience' => '4 года',
                'bio' => 'Мастер перманентного макияжа, бровист, ламимейкер',
                'image' => public_path('img/specialists/nadegdad.jpg'),
            ],
            [
                'user_id' => $dariaId,
                'level_id' => 1,
                'experience' => '5 лет',
                'bio' => 'Ведущий специалист по наращиванию ресниц',
                'image' => public_path('img/specialists/dariad.jpg'),
            ],
            [
                'user_id' => $svetlanaId,
                'level_id' => 1,
                'experience' => '5 лет',
                'bio' => 'Ведущий специалист по наращиванию ресниц',
                'image' => public_path('img/specialists/svet.jpg'),
            ],
            [
                'user_id' => $marianaId,
                'level_id' => 2,
                'experience' => '3 года',
                'bio' => 'Врач косметолог',
                'image' => public_path('img/specialists/mariana.jpg'),
            ],
            [
                'user_id' => $olesiaId,
                'level_id' => 1,
                'experience' => '4 года',
                'bio' => 'Ведущий специалист по наращиванию ресниц',
                'image' => public_path('img/specialists/olesiam.jpg'),
            ],
            [
                'user_id' => $alenaMId,
                'level_id' => 2,
                'experience' => '2 года',
                'bio' => 'Топ мастер по наращиванию ресниц',
                'image' => public_path('img/specialists/alenas.jpg'),
            ],
            [
                'user_id' => $mariasId,
                'level_id' => 2,
                'experience' => '3 года',
                'bio' => 'Топ мастер по наращиванию ресниц, бровист',
                'image' => public_path('img/specialists/marias.jpg'),
            ],
            [
                'user_id' => $anastasiaId,
                'level_id' => 2,
                'experience' => '2 года',
                'bio' => 'Топ мастер по наращиванию ресниц',
                'image' => public_path('img/specialists/anastasias.jpg'),
            ],
            [
                'user_id' => $karinaId,
                'level_id' => 3,
                'experience' => '1 год',
                'bio' => 'Мастер по наращиванию ресниц',
                'image' => public_path('img/specialists/karina.jpg'),
            ],
        ];

        foreach ($specialists as $specialist) {
            $item = Specialist::create(['user_id' => $specialist['user_id'], 'level_id' => $specialist['level_id'], 'experience' => $specialist['experience'], 'bio' => $specialist['bio']]);
            if (File::exists($specialist['image'])) {
                $item->addMedia($specialist['image'])
                    ->preservingOriginal()
                    ->toMediaCollection('specialists');
            }
        }
    }
}
