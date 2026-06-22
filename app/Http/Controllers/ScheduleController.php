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

        $specialist = Auth::user()->specialist;

        if (!$specialist) {
            abort(404, 'Профиль специалиста не найден.');
        }

        $targetDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();


        $appointments = Appointment::where('specialist_id', $specialist->id)
            ->whereBetween('appointment_at', [$startOfWeek, $endOfWeek])
            ->with(['service', 'user'])
            ->orderBy('appointment_at', 'asc')
            ->get();


        $appointmentsByDay = $appointments->groupBy(function ($appointment) {
            return $appointment->appointment_at->format('Y-m-d');
        });


        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weekDays[$day->format('Y-m-d')] = [
                'date' => $day,
                'is_today' => $day->isToday(),
                'appointments' => $appointmentsByDay->get($day->format('Y-m-d'), collect())
            ];
        }


        return view('public.schedule', [
            'weekDays' => $weekDays,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek,
            'specialist' => $specialist
        ]);
    }


}
