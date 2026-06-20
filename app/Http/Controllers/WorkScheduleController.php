<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\User;
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

        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id'    => 'required|exists:services,id',
            'date'          => 'required|date',
        ]);

        $specID = $request->specialist_id;
        $serviceID = $request->service_id;
        $date = $request->date;
        $now = Carbon::now('Asia/Yekaterinburg');


        $schedule = WorkSchedule::where('specialist_id', $specID)->whereDate('work_date', $date)->first();

        if (!$schedule || $schedule->is_day_off){
            return response()->json([]);
        }


        $specialist = Specialist::findOrFail($specID);

        $levelService = DB::table('level_service')
            ->where('service_id', $serviceID)
            ->where('level_id', $specialist->level_id)
            ->first();

        if (!$levelService) {
            return response()->json(['error' => 'Тариф для мастера и услуги не найден'], 422);
        }


        $durationMinutes = (int) $levelService->duration;


        $bookedSlotsQuery = Appointment::where('specialist_id', $specID)
            ->whereDate('appointment_at', $date)
            ->whereIn('status', ['pending', 'confirmed']);

        if ($request->filled('ignore_appointment_id')) {
            $bookedSlotsQuery->where('id', '!=', $request->ignore_appointment_id);
        }


        $appointments = $bookedSlotsQuery->get(['appointment_at', 'service_id'])
            ->map(function($app) use ($specialist) {
                $startTime = Carbon::parse($app->appointment_at);


                $appLevelService = DB::table('level_service')
                    ->where('service_id', $app->service_id)
                    ->where('level_id', $specialist->level_id)
                    ->first();

                $appDuration = $appLevelService ? (int)$appLevelService->duration : 60;
                $endTime = (clone $startTime)->addMinutes($appDuration);

                return [
                    'start' => $startTime,
                    'end' => $endTime
                ];
            });


        $slots = [];


        $dayStart = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->start_time);
        $dayEnd = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->end_time);
        $dayBreakStart = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->break_start);
        $dayBreakEnd = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $schedule->break_end);


        $currentPointer = clone $dayStart;


        while ($currentPointer < $dayEnd) {


            $slotStart = clone $currentPointer;
            $slotEnd = (clone $slotStart)->addMinutes($durationMinutes);


            if ($date == $now->toDateString()) {
                if ($slotStart->timezone('Europe/Moscow') <= $now) {
                    $currentPointer->addMinutes(30);
                    continue;
                }
            }


            if ($slotEnd > $dayEnd) {
                break;
            }

            if ($slotStart < $dayBreakEnd && $slotEnd > $dayBreakStart) {

                $currentPointer = clone $dayBreakEnd;
                continue;
            }


            $isIntersected = false;
            foreach ($appointments as $booked) {
                if ($slotStart < $booked['end'] && $slotEnd > $booked['start']) {
                    $isIntersected = true;
                    $currentPointer = clone $booked['end'];
                    break;
                }
            }


            if (!$isIntersected) {
                $slots[] = $slotStart->format('H:i');


                $currentPointer = clone $slotEnd;
            }
        }

        return response()->json($slots);
    }


    public function store(Request $request)
    {

        $isGuest = $request->has('guest_name') && $request->has('guest_phone');


        if (!$isGuest) {
            if (!Auth::check() && !Auth::guard('sanctum')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Пользователь не авторизован.'
                ], 401);
            }
            $clientId = Auth::id() ?? Auth::guard('sanctum')->id();
            $isAuth = true;
        } else {

            $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_last_name' => 'nullable|string|max:255',
                'guest_phone' => 'required|string|max:20|min:10',
            ]);

            // Ищем или создаем пользователя
            $user = User::where('phone', $request->guest_phone)->first();

            if (!$user) {
                $user = User::create([
                    'first_name' => $request->guest_name,
                    'last_name' => $request->guest_last_name ?? '',
                    'phone' => $request->guest_phone,
                    'email' => null,
                    'password' => null,
                    'role' => 'client',
                ]);
            }

            $clientId = $user->id;
            $isAuth = false;
        }

        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id'    => 'required|exists:services,id',
            'date'          => 'required|date',
            'time'          => 'required|string',
        ]);

        $targetSpecialist = Specialist::find($validated['specialist_id']);
        if ($isAuth && $targetSpecialist && Auth::id() == $targetSpecialist->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Вы не можете записаться на услугу к самому себе.'
            ], 422);
        }

        try {
            $appointmentAt = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);

            if (!$appointmentAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Некорректный формат даты или времени.'
                ], 422);
            }

            $specialist = Specialist::findOrFail($request->specialist_id);

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


            $appointment = Appointment::create([
                'client_id'      => $clientId,
                'specialist_id'  => $validated['specialist_id'],
                'service_id'     => $validated['service_id'],
                'appointment_at' => $appointmentAt,
                'final_price'    => $levelService->price,
                'status'         => 'pending'
            ]);


            if ($isAuth) {
                $user = Auth::user();
                if ($user) {
                    $user->notify(new \App\Notifications\AppointmentNotification($appointment, 'created'));
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Вы успешно записались!',
                'data'    => $appointment
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Критическая ошибка при создании записи: ' . $e->getMessage() . ' в файле ' . $e->getFile() . ' на строке ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Внутренняя ошибка сервера: ' . $e->getMessage()
            ], 500);
        }
    }


}
