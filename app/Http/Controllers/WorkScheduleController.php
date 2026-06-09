<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\WorkSchedule;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkScheduleController extends Controller
{
    public function getAvailableSlots(Request $request)
    {
        // Обязательно требуем service_id, чтобы узнать длительность услуги
        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id'    => 'required|exists:services,id',
            'date'          => 'required|date',
        ]);

        $specID = $request->specialist_id;
        $serviceID = $request->service_id;
        $date = $request->date;
        $now = Carbon::now();

        // 1. Получаем расписание мастера
        $schedule = WorkSchedule::where('specialist_id', $specID)->whereDate('work_date', $date)->first();

        if (!$schedule || $schedule->is_day_off){
            return response()->json([]);
        }

        // 2. Находим мастера и вытаскиваем длительность услуги из level_service
        $specialist = Specialist::findOrFail($specID);

        $levelService = DB::table('level_service')
            ->where('service_id', $serviceID)
            ->where('level_id', $specialist->level_id)
            ->first();

        if (!$levelService) {
            return response()->json(['error' => 'Тариф для мастера и услуги не найден'], 422);
        }

        // Длительность услуги в минутах (например, 120 или 150)
        $durationMinutes = (int) $levelService->duration;

        // 3. Собираем уже занятые визиты на этот день
        $bookedSlotsQuery = Appointment::where('specialist_id', $specID)
            ->whereDate('appointment_at', $date)
            ->whereIn('status', ['pending', 'confirmed']);

        if ($request->filled('ignore_appointment_id')) {
            $bookedSlotsQuery->where('id', '!=', $request->ignore_appointment_id);
        }

        // Получаем занятые интервалы в виде объектов Carbon (чтобы удобно сравнивать)
        $appointments = $bookedSlotsQuery->get(['appointment_at', 'service_id'])
            ->map(function($app) use ($specialist) {
                $startTime = Carbon::parse($app->appointment_at);

                // Нам нужно знать, сколько длится уже занятая услуга, чтобы построить её конец
                $appLevelService = DB::table('level_service')
                    ->where('service_id', $app->service_id)
                    ->where('level_id', $specialist->level_id)
                    ->first();

                $appDuration = $appLevelService ? (int)$appLevelService->duration : 60; // дефолт 60 мин, если не нашли
                $endTime = (clone $startTime)->addMinutes($appDuration);

                return [
                    'start' => $startTime,
                    'end' => $endTime
                ];
            });

        // 4. Генерируем потенциальные слоты (шаг начала записи оставляем 30 минут)
        $slots = [];

        // Переводим границы дня в объекты Carbon для точного сравнения
        $dayStart = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->start_time);
        $dayEnd = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->end_time);
        $dayBreakStart = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->break_start);
        $dayBreakEnd = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->break_end);

        // Начинаем проверку с самого начала рабочего дня мастера
        $currentPointer = clone $dayStart;

        // Крутим цикл, пока маркер времени не дошел до конца рабочего дня
        while ($currentPointer < $dayEnd) {

            // Время гипотетического окончания процедуры
            $slotStart = clone $currentPointer;
            $slotEnd = (clone $slotStart)->addMinutes($durationMinutes);

            // А: Если проверяем сегодняшний день, нельзя предлагать время, которое уже прошло
            if ($date == $now->toDateString() && $slotStart <= $now) {
                // Сдвигаем указатель на 30 минут вперед, чтобы найти ближайшее будущее время
                $currentPointer->addMinutes(30);
                continue;
            }

            // Б: Проверка, что процедура целиком влезает до конца рабочего дня
            if ($slotEnd > $dayEnd) {
                break; // Если не влезает до закрытия салона — дальше искать нет смысла
            }

            // В: Проверка на пересечение с обеденным перерывом
            // Сеанс пересекается с обедом, если начинается раньше конца обеда И заканчивается позже начала обеда
            if ($slotStart < $dayBreakEnd && $slotEnd > $dayBreakStart) {
                // Важно! Вместо того чтобы ломать всё, мы просто переносим начало записи на конец обеда
                $currentPointer = clone $dayBreakEnd;
                continue;
            }

            // Г: Проверка на пересечение с уже существующими записями других клиентов
            $isIntersected = false;
            foreach ($appointments as $booked) {
                if ($slotStart < $booked['end'] && $slotEnd > $booked['start']) {
                    $isIntersected = true;
                    // Если наткнулись на чужую запись, переносим наш маркер на время окончания этой записи
                    $currentPointer = clone $booked['end'];
                    break;
                }
            }

            // Если все проверки пройдены удачно — это наш честный слот!
            if (!$isIntersected) {
                $slots[] = $slotStart->format('H:i');

                // Шагаем маркером СТРОГО на длительность выполненной услуги (например, на 2 часа вперед)
                $currentPointer = clone $slotEnd;
            }
        }

        return response()->json($slots);
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

            // 2. Находим мастера, чтобы узнать его категорию (level_id)
            $specialist = Specialist::findOrFail($request->specialist_id);

            // 3. КРИТИЧЕСКИЙ ШАГ: Ищем правильную цену в сводной таблице level_service
            $levelService = DB::table('level_service')
                ->where('service_id', $request->service_id)
                ->where('level_id', $specialist->level_id)
                ->first();

            if (!$levelService) {
                return response()->json([
                    'success' => false,
                    'message' => "В базе level_service нет цены для service_id: {$validated['service_id']} и level_id мастера: " . ($specialist->level_id ?? 'NULL')
                ], 422);
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
                'final_price'    => $levelService->price, // Обязательное поле для БД
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
