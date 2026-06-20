<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    public function index()
    {
        $specialists = Specialist::with('user')->get()->sortBy(fn ($specialist) => $specialist->user->last_name ?? '')->values();
        return view('admin.specialists.index', compact('specialists'));
    }

    public function create(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $existingSpecialists = Specialist::pluck('user_id')->toArray();
        $users = $query->whereNotIn('id', $existingSpecialists)->paginate(15)->withQueryString();

        return view('admin.specialists.select_user', compact('users'));
    }

    public function build(User $user)
    {
        if (Specialist::where('user_id', $user->id)->exists()) {
            return redirect()->route('admin.specialists.create')->with('error', 'Этот пользователь уже стал специалистом.');
        }

        $levels = Level::all();
        $services = Service::all();

        return view('admin.specialists.create', compact('user', 'levels', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,id|unique:specialists,user_id',
            'level_id'   => 'required|exists:levels,id',
            'experience' => 'nullable|string|max:255',
            'bio'        => 'nullable|string',
            'services'   => 'nullable|array',
            'services.*' => 'exists:services,id',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'user_id.required'  => 'Выберите пользователя, который станет специалистом.',
            'user_id.exists'    => 'Выбранный пользователь не найден в системе.',
            'user_id.unique'    => 'Этот пользователь уже является специалистом салона.',
            'level_id.required' => 'Выберите уровень квалификации мастера.',
            'photo.image'       => 'Загрузите изображение в формате JPEG, PNG, JPG, GIF или SVG.',
            'photo.max'         => 'Размер фото не должен превышать 2 МБ.',
        ]);

        $specialist = Specialist::create([
            'user_id'    => $validated['user_id'],
            'level_id'   => $validated['level_id'],
            'experience' => $validated['experience'] ?? null,
            'bio'        => $validated['bio'] ?? null,
        ]);

        if (!empty($validated['services'])) {
            $specialist->service_specialist()->sync($validated['services']);
        }

        // Сохранение фото в коллекцию 'specialists'
        if ($request->hasFile('photo')) {
            $specialist->addMedia($request->file('photo'))->toMediaCollection('specialists');
        }

        return redirect()->route('admin.specialists.index')
            ->with('success', 'Специалист успешно добавлен.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Specialist $specialist)
    {
        $levels = Level::select('id', 'name')->get();
        $services = Service::all();
        $assignedServices = $specialist->service_specialist()->pluck('services.id')->toArray();

        return view('admin.specialists.edit', compact('specialist', 'levels', 'services', 'assignedServices'));
    }

    public function update(Request $request, Specialist $specialist)
    {
        $validated = $request->validate([
            'level_id'   => ['required', 'integer', 'exists:levels,id'],
            'experience' => ['nullable', 'string', 'max:255'],
            'bio'        => ['nullable', 'string'],
            'services'   => ['nullable', 'array'],
            'services.*' => ['exists:services,id'],
            'photo'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'level_id.required' => 'Выберите уровень квалификации мастера.',
            'level_id.exists'   => 'Выбранный уровень не существует в системе.',
            'experience.max'    => 'Поле "Опыт работы" не должно превышать 255 символов.',
            'photo.image'       => 'Загрузите изображение в формате JPEG, PNG, JPG, GIF или SVG.',
            'photo.max'         => 'Размер фото не должен превышать 2 МБ.',
        ]);

        $specialist->update([
            'level_id'   => $validated['level_id'],
            'experience' => $validated['experience'] ?? null,
            'bio'        => $validated['bio'] ?? null,
        ]);

        // Синхронизация услуг
        $specialist->service_specialist()->sync($request->services ?? []);

        // Добавляем новое фото в коллекцию 'specialists' (без удаления старого)
        if ($request->hasFile('photo')) {
            $specialist->addMedia($request->file('photo'))->toMediaCollection('specialists');
        }

        return redirect()->route('admin.specialists.index')
            ->with('success', 'Данные мастера успешно обновлены.');
    }

    public function destroy(Specialist $specialist)
    {
        // Удаляем связанные медиа из коллекции 'specialists'
        $specialist->clearMediaCollection('specialists');
        $specialist->delete();

        return redirect()->route('admin.specialists.index')
            ->with('success', 'Специалист успешно удален.');
    }
}
