<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkScheduleSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('work_schedules')->delete();

        $specialists = Specialist::all();

        if ($specialists->count() < 4) {
            $this->command->warn('У тебя меньше 4 мастеров в базе. График 2/2 распределится по порядку.');
        }

        $data = [];
        $startDate = Carbon::today();


        for ($i = 0; $i < 30; $i++) {
            $currentDate = (clone $startDate)->addDays($i);

            foreach ($specialists as $index => $specialist) {

                $isFirstGroup = ($index % 2 === 0);
                $dayInCycle = $i % 4;

                $isWorking = false;

                if ($isFirstGroup && ($dayInCycle === 0 || $dayInCycle === 1)) {
                    $isWorking = true;
                } elseif (!$isFirstGroup && ($dayInCycle === 2 || $dayInCycle === 3)) {
                    $isWorking = true;
                }

                if ($isWorking) {
                    $data[] = [
                        'specialist_id' => $specialist->id,
                        'work_date'     => $currentDate->format('Y-m-d'),
                        'start_time'    => '09:00:00',
                        'end_time'      => '21:00:00',
                        'break_start'   => '14:00:00',
                        'break_end'     => '15:00:00',
                        'is_day_off'    => false,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
            }
        }

        DB::table('work_schedules')->insert($data);
        $this->command->info('График 2/2 для мастеров успешно создан!');
    }
}
