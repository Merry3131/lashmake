<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Specialist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Получаем профиль специалиста для текущего авторизованного пользователя
        $specialist = Auth::user()->specialist; // Убедись, что в модели User настроена связь hasOne(Specialist::class)

        if (!$specialist) {
            abort(404, 'Профиль специалиста не найден.');
        }

        // 2. Определяем границы текущей недели (с понедельника по воскресенье)
        // Если передан параметр ?date=YYYY-MM-DD, берем неделю от этой даты, иначе текущую
        $targetDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $targetDate->copy()->startOfWeek(); // Понедельник 00:00
        $endOfWeek = $targetDate->copy()->endOfWeek();     // Воскресенье 23:59

        // 3. Получаем все записи мастера на эту неделю с подгрузкой услуг и клиентов
        $appointments = Appointment::where('specialist_id', $specialist->id)
            ->whereBetween('appointment_at', [$startOfWeek, $endOfWeek])
            ->with(['service', 'user']) // Жа canная загрузка (Eager Loading) для оптимизации запросов
            ->orderBy('appointment_at', 'asc')
            ->get();

        // 4. Группируем полученные записи по дням недели для удобного вывода в Blade
        $appointmentsByDay = $appointments->groupBy(function ($appointment) {
            return $appointment->appointment_at->format('Y-m-d');
        });

        // 5. Генерируем массив всех 7 дней недели, чтобы вывести даже пустые дни (без записей)
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weekDays[$day->format('Y-m-d')] = [
                'date' => $day,
                'is_today' => $day->isToday(),
                'appointments' => $appointmentsByDay->get($day->format('Y-m-d'), collect())
            ];
        }

        // Передаем данные в представление
        return view('public.schedule', [
            'weekDays' => $weekDays,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek,
            'specialist' => $specialist
        ]);
    }


}
