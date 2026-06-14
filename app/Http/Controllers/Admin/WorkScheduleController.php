<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $targetDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        // Загружаем мастеров вместе с их графиком на выбранную неделю
        $specialists = Specialist::with(['user', 'service_specialist'])->get();

        $schedules = WorkSchedule::whereBetween('work_date', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(['specialist_id', function ($item) {
                return $item->work_date->format('Y-m-d');
            }]);

        // Генерируем массив дней недели для построения таблицы
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $startOfWeek->copy()->addDays($i);
        }

        return view('admin.schedule.index', [
            'specialists' => $specialists,
            'weekDays' => $weekDays,
            'schedules' => $schedules,
            'startOfWeek' => $startOfWeek,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'date' => 'required|date_format:Y-m-d'
        ]);

        // Создаем пустой экземпляр модели в памяти (НЕ сохраняя в БД)
        $schedule = new WorkSchedule();
        $schedule->specialist_id = $request->input('specialist_id');
        $schedule->work_date = \Carbon\Carbon::parse($request->input('date'));

        // Ставим дефолтные значения для нового дня
        $schedule->is_day_off = 0;
        $schedule->start_time = '09:00';
        $schedule->end_time = '21:00';

        // Подгружаем мастера для отображения имени в шапке
        $schedule->load('specialist.user');

        // Используем ту же вьюху! Но подменяем роут в форме (об этом ниже)
        return view('admin.schedule.edit', compact('schedule'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'work_date'     => 'required|date',
            'is_day_off'    => 'required|boolean',
            'start_time'    => 'required_if:is_day_off,0|nullable|date_format:H:i',
            'end_time'      => 'required_if:is_day_off,0|nullable|date_format:H:i|after:start_time',
            'break_start'   => 'nullable|date_format:H:i',
            'break_end'     => 'nullable|date_format:H:i|after:break_start',
        ]);

        // Если день выходной — принудительно очищаем время
        if ($request->input('is_day_off') == 1) {
            $validated['start_time'] = null;
            $validated['end_time'] = null;
            $validated['break_start'] = null;
            $validated['break_end'] = null;
        }

        $schedule = WorkSchedule::create($validated);

        return redirect()
            ->route('admin.schedule.index', ['date' => \Carbon\Carbon::parse($schedule->work_date)->format('Y-m-d')])
            ->with('success', 'График на день успешно создан!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = WorkSchedule::with('specialist.user')->findOrFail($id);
        return view('admin.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = WorkSchedule::findOrFail($id);

        $validated = $request->validate([
            'is_day_off' => 'boolean',
            'start_time' => 'required_if:is_day_off,0|nullable|date_format:H:i',
            'end_time' => 'required_if:is_day_off,0|nullable|date_format:H:i|after:start_time',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i|after:break_start',
        ]);

        // Если администратор делает день выходным, очищаем часы или ставим дефолтные
        if ($request->input('is_day_off') == 1) {
            $validated['start_time'] = null;
            $validated['end_time'] = null;
            $validated['break_start'] = null;
            $validated['break_end'] = null;
        }

        $schedule->update($validated);

        return redirect()
            ->route('admin.schedule.index', ['date' => $schedule->work_date->format('Y-m-d')])
            ->with('success', 'График работы успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
