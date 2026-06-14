<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Алена',
                'last_name' => 'Хабибуллина',
                'email' => 'habibullina_alena@gmail.com',
                'password' => Hash::make('qwerty'),
                'phone' => '+79086541212',
                'role' => 'admin'
            ],
            [
                'first_name' => 'Надежда',
                'last_name' => '',
                'email' => 'nadegda@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79082342343',
                'role' => 'master'
            ],
            [
                'first_name' => 'Светлана',
                'last_name' => '',
                'email' => 'svetlata@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '+79991233456',
                'role' => 'master'
            ],
            [
                'first_name' => 'Марьяна',
                'last_name' => '',
                'email' => 'mariana@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Дарья',
                'last_name' => 'Д',
                'email' => 'dariad@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Надежда',
                'last_name' => 'Р',
                'email' => 'nadegnar@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Олеся',
                'last_name' => 'М',
                'email' => 'olesia@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Алена',
                'last_name' => 'М',
                'email' => 'alenam@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Мария',
                'last_name' => 'С',
                'email' => 'marias@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Анастасия',
                'last_name' => 'с',
                'email' => 'anastasia@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Карина',
                'last_name' => 'С',
                'email' => 'karina@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ]

        ];
        DB::table('users')->insert($users);
    }
}
