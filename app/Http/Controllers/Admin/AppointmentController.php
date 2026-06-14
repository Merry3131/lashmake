<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\LevelService;
use App\Models\User;
use App\Notifications\AppointmentNotification;
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
        // Получаем всех пользователей с ролью 'client', отсортированных по фамилии
        $users = User::where('role', 'client')->orderBy('last_name')->get();

        // Получаем всех активных специалистов
        $specialists = \App\Models\Specialist::with('user')->get();

        // Получаем все услуги
        $services = \App\Models\Service::all();

        return view('admin.appointments.create', compact('users', 'specialists', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Динамические правила валидации
        $rules = [
            'user_mode'        => ['required', 'in:new,existing'],
            'service_id'       => ['required', 'exists:services,id'],
            'specialist_id'    => ['required', 'exists:specialists,id'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'date_format:H:i'], // Изменили с date на date_format
        ];

        // Если создаем НОВОГО клиента — имя и телефон обязательны
        if ($request->user_mode == 'new') {
            $rules['name']  = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string'];
        }
        // Если выбираем СУЩЕСТВУЮЩЕГО — обязателен user_id
        else {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        // Теперь запускаем валидацию
        $validated = $request->validate($rules);

        // 2. Логика определения/создания пользователя
        if ($request->user_mode == 'new') {

            // Очищаем телефон от лишних символов (пробелы, скобки, тире), оставляем цифры и плюс
            $cleanPhone = preg_replace('/[^0-9+]/', '', $validated['phone']);

            // Проверяем на всякий случай, вдруг такой телефон уже заведен в системе
            $existingUser = User::where('phone', $cleanPhone)->first();

            if ($existingUser) {
                $userId = $existingUser->id;
            } else {
                // Создаем нового пользователя. Так как email и password в базе Laravel
                // обычно non-nullable, генерируем для них технические уникальные данные
                $newUser = User::create([
                    'last_name' => $validated['last_name'],
                    'first_name' => $validated['name'],
                    'phone' => $cleanPhone,
                    'role' => 'client', // Задаем роль по умолчанию
                ]);

                $userId = $newUser->id;
            }
        } else {
            // Если берем готового из списка
            $userId = $validated['user_id'];
        }

        // 3. Формируем дату и время (appointment_at) для записи
        $appointmentAt = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // 4. Расчет стоимости услуги в зависимости от квалификации мастера
        $specialist = \App\Models\Specialist::find($validated['specialist_id']);
        $finalPrice = 0;

        // Ищем прайс-лист для связки Уровень Мастера + Услуга
        $levelService = LevelService::where('level_id', $specialist->level_id)
            ->where('service_id', $validated['service_id'])
            ->first();

        if ($levelService) {
            $finalPrice = $levelService->price;
        }

        // 5. Сохраняем саму запись в базу данных
        Appointment::create([
            'client_id' => $userId,
            'specialist_id' => $validated['specialist_id'],
            'service_id' => $validated['service_id'],
            'appointment_at' => $appointmentAt,
            'final_price' => $finalPrice,
            'status' => 'pending', // Администратор создает лично, значит статус сразу подтвержден
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', $request->has('is_new_client')
                ? 'Новый клиент успешно добавлен в базу и записан на процедуру!'
                : 'Запись успешно создана.');
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
            ->sortBy(fn($s) => $s->user->last_name ?? '')
            ->values();


        return view('admin.appointments.edit', compact('appointment', 'specialists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'specialist_id' => ['required', 'exists:specialists,id'],
            'appointment_date' => ['required', 'date_format:Y-m-d'], // Отдельное поле даты в форме
            'appointment_time' => ['required', 'date_format:H:i'],   // Отдельное поле времени в форме
            'status' => ['required', 'in:pending,approved,confirmed,cancelled'],
        ], [
            'specialist_id.exists' => 'Выбранный специалист не найден в системе.',
            'status.in' => 'Указан некорректный статус записи.',
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
            'specialist_id' => $validated['specialist_id'],
            'appointment_at' => $appointmentAt,
            'status' => $validated['status'],
        ]);

        $client = $appointment->user;
        $client->notify(new AppointmentNotification($appointment, 'confirmed'));

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Запись успешно изменена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index');
    }
}
