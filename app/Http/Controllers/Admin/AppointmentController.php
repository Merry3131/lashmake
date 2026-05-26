<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\LevelService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with([
            'specialist.user',
            'user',
            'service'
        ])->orderBy('appointment_at', 'desc')->get();

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.appointments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Валидация данных формы админа
        $request->validate([
            'phone' => ['required', 'string'], // Номер телефона, который продиктовали
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'service_id' => ['required', 'exists:services,id'],
            'specialist_id' => ['required', 'exists:specialists,id'],
            'appointment_time' => ['required', 'date'],
        ]);

        // 2. Магия: Ищем или автоматически создаем пользователя
        // Поиск идет ТОЛЬКО по телефону
        $user = User::firstOrCreate(
            ['phone' => $request->phone], // По какому полю искать
            [                             // Что записать, если не найден
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => null,
                'password' => null, // Пароля нет, так как он не регистрировался сам
            ]
        );

        // 3. Создаем саму запись, привязывая её к ID (найденному или только что созданному)
        Appointment::create([
            'user_id' => $user->id, // Теперь у нас ВСЕГДА есть корректный user_id
            'service_id' => $request->service_id,
            'specialist_id' => $request->specialist_id,
            'appointment_time' => $request->appointment_time,
            'status' => 'confirmed', // Администратор записывает лично, значит статус сразу подтвержден
        ]);

        return redirect()->back()->with('success', 'Клиент успешно записан!');
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
    public function edit(Appointment $appointment)
    {
        $specialists = \App\Models\Specialist::with('user')->get()
            ->sortBy(fn ($s) => $s->user->last_name ?? '')
            ->values();


        return view('admin.appointments.edit', compact('appointment', 'specialists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'specialist_id'    => ['required', 'exists:specialists,id'],
            'appointment_date' => ['required', 'date_format:Y-m-d'], // Отдельное поле даты в форме
            'appointment_time' => ['required', 'date_format:H:i'],   // Отдельное поле времени в форме
            'status'           => ['required', 'in:pending,confirmed,cancelled'],
        ], [
            'specialist_id.exists' => 'Выбранный специалист не найден в системе.',
            'status.in'            => 'Указан некорректный статус записи.',
        ]);

        // 2. Собираем дату и время в один объект Carbon для базы данных
        $appointmentAt = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // 3. Если админ изменил мастера, нужно проверить и обновить стоимость услуги
        if ($appointment->specialist_id != $validated['specialist_id']) {
            // Находим цену для выбранной услуги у нового мастера (через его уровень квалификации)
            $newSpecialist = \App\Models\Specialist::find($validated['specialist_id']);

            $levelService = LevelService::where('level_id', $newSpecialist->level_id)
                ->where('service_id', $appointment->service_id)
                ->first();

            if ($levelService) {
                $appointment->final_price = $levelService->price;
            }
        }

        // 4. Обновляем параметры записи
        $appointment->update([
            'specialist_id'  => $validated['specialist_id'],
            'appointment_at' => $appointmentAt,
            'status'         => $validated['status'],
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Запись успешно изменена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {

    }
}
