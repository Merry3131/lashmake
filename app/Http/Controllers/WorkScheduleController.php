<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function getAvailableSlots(Request $request){
        //получаем параметры из ajax запроса
        $specID = $request->specialist_id;
        $date = $request->date;
        $now = Carbon::now();

        // находим график мастера на этот день
        $schedule = WorkSchedule::where('specialist_id', $specID)->whereDate('work_date', $date)->first();

        // если записи нет или выходной у мастера - возвращаем пустой список
        if (!$schedule || $schedule->is_day_off){
            return response()->json([]);
        }

        //генерируем время для записи
        $slots = [];
        $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
        $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);
        $breakStart = Carbon::createFromFormat('H:i:s', $schedule->break_start);
        $breakEnd = Carbon::createFromFormat('H:i:s', $schedule->break_end);

        //интервал по 30 минут
        $intervals = CarbonInterval::minutes(30)->toPeriod($startTime, $endTime);

        foreach ($intervals as $slot){
            $currentSlot = $slot->format('H:i');
            // проверка на прошедшее время
            if ($date == $now->toDateString() && $slot->format('H:i') <= $now->format('H:i')){
                continue;
            }
            //проверка на перерыв
            $isInsideBreak = ($currentSlot >= $breakStart->format('H:i') && $currentSlot < $breakEnd->format('H:i'));

            //проверка на конец смены
            if(!$isInsideBreak && $currentSlot < $endTime->format('H:i')){
                $slots[] = $currentSlot;
            }
        }

        // сверка с занятыит записями
        $bookedSlots = Appointment::where('specialist_id', $specID)
            ->whereDate('appointment_at', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('appointment_at')
            ->map(function($datetime){
                return Carbon::parse($datetime)->format('H:i');
            })
            ->toArray();

        //убираем занятое время из общего списка
        $availableSlots = array_values(array_diff($slots, $bookedSlots));

        return response()->json($availableSlots);
    }

    // создание новой записи на услугу
    public function store(Request $request){
        //данные для валидации
        $validated = $request->validate([
           'specialist_id' => 'required|exists:specialists,id',
           'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $appointmentAt = Carbon::parse($validated['date'] . ' ' . $validated['time']);

        Appointment::create([
            'user_id' => auth()->id(),
            'specialist_id' => $validated['specialist_id'],
            'service_id' => $validated['service_id'],
            'appointment_at' => $appointmentAt,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Вы успешно записаны!']);
    }
}
