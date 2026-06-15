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
    public function index()
    {
        $appointments = Appointment::with([
            'specialist.user',
            'user',
            'service'
        ])->orderBy('appointment_at', 'desc')->get();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $users = User::where('role', 'client')->orderBy('last_name')->get();
        $specialists = \App\Models\Specialist::with('user', 'service_specialist')->get();
        $services = \App\Models\Service::all();

        return view('admin.appointments.create', compact('users', 'specialists', 'services'));
    }

    public function store(Request $request)
    {
        $rules = [
            'user_mode'        => ['required', 'in:new,existing'],
            'service_id'       => ['required', 'exists:services,id'],
            'specialist_id'    => ['required', 'exists:specialists,id'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'date_format:H:i'],
        ];

        if ($request->user_mode == 'new') {
            $rules['name']  = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string'];
        } else {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        $specialist = \App\Models\Specialist::find($validated['specialist_id']);
        $serviceExists = $specialist->service_specialist()->where('service_id', $validated['service_id'])->exists();

        if (!$serviceExists) {
            return redirect()->back()
                ->withErrors(['service_id' => 'Данный мастер не оказывает выбранную услугу.'])
                ->withInput();
        }

        if ($request->user_mode == 'new') {
            $cleanPhone = preg_replace('/[^0-9+]/', '', $validated['phone']);
            $existingUser = User::where('phone', $cleanPhone)->first();

            if ($existingUser) {
                $userId = $existingUser->id;
            } else {
                $newUser = User::create([
                    'last_name' => $validated['last_name'],
                    'first_name' => $validated['name'],
                    'phone' => $cleanPhone,
                    'role' => 'client',
                ]);
                $userId = $newUser->id;
            }
        } else {
            $userId = $validated['user_id'];
        }

        $appointmentAt = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        $finalPrice = 0;
        $levelService = LevelService::where('level_id', $specialist->level_id)
            ->where('service_id', $validated['service_id'])
            ->first();

        if ($levelService) {
            $finalPrice = $levelService->price;
        }

        Appointment::create([
            'client_id' => $userId,
            'specialist_id' => $validated['specialist_id'],
            'service_id' => $validated['service_id'],
            'appointment_at' => $appointmentAt,
            'final_price' => $finalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Запись успешно создана.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Appointment $appointment)
    {
        $specialists = \App\Models\Specialist::with('user', 'service_specialist')->get()
            ->sortBy(fn($s) => $s->user->last_name ?? '')
            ->values();

        return view('admin.appointments.edit', compact('appointment', 'specialists'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'specialist_id' => ['required', 'exists:specialists,id'],
            'service_id'    => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date_format:Y-m-d'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:pending,approved,completed,cancelled'],
        ], [
            'specialist_id.exists' => 'Выбранный специалист не найден в системе.',
            'service_id.exists' => 'Выбранная услуга не найдена.',
            'status.in' => 'Указан некорректный статус записи.',
        ]);

        $specialist = \App\Models\Specialist::find($validated['specialist_id']);
        $serviceExists = $specialist->service_specialist()->where('service_id', $validated['service_id'])->exists();

        if (!$serviceExists) {
            return redirect()->back()
                ->withErrors(['service_id' => 'Данный мастер не оказывает выбранную услугу.'])
                ->withInput();
        }

        $appointmentAt = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        if ($appointment->specialist_id != $validated['specialist_id'] || $appointment->service_id != $validated['service_id']) {
            $levelService = LevelService::where('level_id', $specialist->level_id)
                ->where('service_id', $validated['service_id'])
                ->first();

            if ($levelService) {
                $appointment->final_price = $levelService->price;
            }
        }

        $appointment->update([
            'specialist_id' => $validated['specialist_id'],
            'service_id' => $validated['service_id'],
            'appointment_at' => $appointmentAt,
            'status' => $validated['status'],
        ]);

        $client = $appointment->user;
        $client->notify(new AppointmentNotification($appointment, 'confirmed'));

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Запись успешно изменена.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index');
    }
}
