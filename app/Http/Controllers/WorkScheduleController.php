<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\WorkSchedule;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        // 1. Проверяем, авторизован ли пользователь (на всякий случай)
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не авторизован.'
            ], 401);
        }

        // 2. Валидация входящих данных
        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id'    => 'required|exists:services,id',
            'date'          => 'required|date',
            'time'          => 'required|string',
        ]);

        try {
            // 3. Безопасное склеивание даты и времени через специальный метод Carbon
            // Из "2026-05-20" и "14:30" создаем полноценный объект даты-времени
            $appointmentAt = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);

            if (!$appointmentAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Некорректный формат даты или времени.'
                ], 422);
            }

            // 4. Находим услугу, чтобы взять её базовую стоимость
            $service = Service::find($validated['service_id']);
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Услуга не найдена в базе данных.'
                ], 404);
            }

            // 5. Проверяем, не занято ли это время у мастера (учитываем статус cancelled)
            $isTimeOccupied = Appointment::where('specialist_id', $validated['specialist_id'])
                ->where('appointment_at', $appointmentAt)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($isTimeOccupied) {
                return response()->json([
                    'success' => false,
                    'message' => 'К сожалению, это время уже занято.'
                ], 422);
            }

            // 6. Запись в базу данных
            $appointment = Appointment::create([
                'client_id'      => Auth::id(), // Записываем ID текущего пользователя
                'specialist_id'  => $validated['specialist_id'],
                'service_id'     => $validated['service_id'],
                'appointment_at' => $appointmentAt,
                'final_price'    => $service->base_price, // Обязательное поле для БД
                'status'         => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Вы успешно записались!',
                'data'    => $appointment
            ], 201);

        } catch (\Exception $e) {
            // Это запишет детальный текст ошибки (какой именно файл или строка упали) в файл storage/logs/laravel.log
            \Log::error('Критическая ошибка при создании записи: ' . $e->getMessage() . ' в файле ' . $e->getFile() . ' на строке ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Внутренняя ошибка сервера: ' . $e->getMessage()
            ], 500);
        }
    }


}
