<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentsSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = [
            [
                'client_id' => 1,
                'specialist_id' => 1,
                'service_id' => 1,
                'appointment_at' => Carbon::now()->subDays(1)->setTime(10, 0, 0),
                'final_price' => 2500.00,
                'status' => 'completed',
                'notes' => 'Клиентка очень довольна, просила в следующий раз тот же изгиб.',
            ],
            [
                'client_id' => 2,
                'specialist_id' => 1,
                'service_id' => 3,
                'appointment_at' => Carbon::now()->addDays(2)->setTime(14, 30, 0),
                'final_price' => 1500.00,
                'status' => 'approved',
                'notes' => 'Нужна коррекция воском.',
            ],
            [
                'client_id' => 3,
                'specialist_id' => 2,
                'service_id' => 2,
                'appointment_at' => Carbon::now()->addHours(5),
                'final_price' => 3000.00,
                'status' => 'pending',
                'notes' => null,
            ],
            [
                'client_id' => 1,
                'specialist_id' => 2,
                'service_id' => 1,
                'appointment_at' => Carbon::now()->subWeek(),
                'final_price' => 2500.00,
                'status' => 'completed',
                'notes' => 'Повторный визит.',
            ],
        ];

        foreach ($appointments as $data) {
            Appointment::create($data);
        }
    }
}
