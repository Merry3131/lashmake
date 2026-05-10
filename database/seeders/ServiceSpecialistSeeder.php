<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceSpecialist = [
            [
                'service_id' => 1,
                'specialist_id' => 1
            ],
            [
                'service_id' => 2,
                'specialist_id' => 2
            ],
            [
                'service_id' => 3,
                'specialist_id' => 3
            ],
            [
                'service_id' => 4,
                'specialist_id' => 4
            ],
            [
                'service_id' => 4,
                'specialist_id' => 1
            ],
            [
                'service_id' => 6,
                'specialist_id' => 2
            ],
        ];

        foreach ($serviceSpecialist as $row) {
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();

            DB::table('service_specialist')->insert($row);
        }
    }
}
