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
                'last_name' => '',
                'email' => 'habibullina_alena@gmail.com',
                'password' => Hash::make('111'),
                'phone' => '+79086541212',
                'role' => 'admin'
            ],
            [
                'first_name' => 'Надежда',
                'last_name' => '',
                'email' => 'nadegda@mail.ru',
                'password' => Hash::make('надеждаХ'),
                'phone' => '+79082342343',
                'role' => 'master'
            ],
            [
                'first_name' => 'Надежда',
                'last_name' => 'Р',
                'email' => 'nadejda_r@gmail.com',
                'password' => Hash::make('надеждаР'),
                'phone' => '+79991233456',
                'role' => 'master'
            ],
            [
                'first_name' => 'Дарья',
                'last_name' => 'Д',
                'email' => 'daria_d@mail.ru',
                'password' => Hash::make('ДарьяД'),
                'phone' => '+79086547878',
                'role' => 'master'
            ]

        ];
        DB::table('users')->insert($users);
    }
}
