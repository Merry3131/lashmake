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
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'kristinafisin@gmail.com',
                'password' => Hash::make('qwerty'),
                'phone' => '+79086541212',
                'role' => 'admin'
            ],
            [
                'first_name' => 'Надежда',
                'last_name' => 'Хабибуллина',
                'email' => 'nadegda@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79082342343',
                'role' => 'master'
            ],
            [
                'first_name' => 'Светлана',
                'last_name' => 'Петрова',
                'email' => 'svetlata@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '+79991233456',
                'role' => 'master'
            ],
            [
                'first_name' => 'Марьяна',
                'last_name' => 'Сидорова',
                'email' => 'mariana@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Дарья',
                'last_name' => 'Шевченко',
                'email' => 'dariad@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Надежда',
                'last_name' => 'Никитина',
                'email' => 'nadegnar@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Олеся',
                'last_name' => 'Верещак',
                'email' => 'olesia@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Алена',
                'last_name' => 'Шарова',
                'email' => 'alenam@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Мария',
                'last_name' => 'Мамедова',
                'email' => 'marias@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Анастасия',
                'last_name' => 'Фролова',
                'email' => 'anastasia@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ],
            [
                'first_name' => 'Карина',
                'last_name' => 'Султинский',
                'email' => 'karina@mail.ru',
                'password' => Hash::make('12345678'),
                'phone' => '+79086547878',
                'role' => 'master'
            ]

        ];
        DB::table('users')->insert($users);
    }
}
