<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Works;
use App\Models\Specialist;
use App\Models\Service;
use Illuminate\Http\Request;

class ExampleOfWorkController extends Controller
{
    /**
     * Вывод списка всех примеров работ
     */
    public function index()
    {
        // Ленивая загрузка связей и медиафайлов для оптимизации запросов
        $works = Works::with(['specialist.user', 'service', 'media'])->latest()->get();
        return view('admin.works.index', compact('works'));
    }

    /**
     * Форма создания новой работы
     */
    public function create()
    {
        $specialists = Specialist::with('user')->get();
        $services = Service::all();
        return view('admin.works.create', compact('specialists', 'services'));
    }

    /**
     * Сохранение новой работы в базу данных и прикрепление фото
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id'    => 'required|exists:services,id',
            'description'   => 'nullable|string',
            'image'         => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // макс 5МБ
        ]);

        $work = Works::create($validated);

        // Добавляем изображение в коллекцию 'images' библиотеки Spatie
        if ($request->hasFile('image')) {
            $work->addMediaFromRequest('image')
                ->toMediaCollection('works');
        }

        return redirect()->route('admin.works.index')
            ->with('success', 'Пример работы успешно добавлен в портфолио!');
    }

    /**
     * Форма редактирования существующей работы
     */
    public function edit(string $id)
    {
        $work = Works::findOrFail($id);
        $specialists = Specialist::with('user')->get();
        $services = Service::all();

        return view('admin.works.edit', compact('work', 'specialists', 'services'));
    }

    /**
     * Обновление данных работы и замена фото (при необходимости)
     */
    public function update(Request $request, string $id)
    {
        $work = Works::findOrFail($id);

        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id'    => 'required|exists:services,id',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $work->update($validated);

        // Если загружено новое изображение, старое в коллекции 'images' автоматически заменится
        if ($request->hasFile('image')) {
            // Очищаем прошлую коллекцию картинок (если Spatie настроен на одиночный файл)
            $work->clearMediaCollection('works')->addMediaFromRequest('image')
                ->toMediaCollection('works');
        }

        return redirect()->route('admin.works.index')
            ->with('success', 'Информация о работе успешно обновлена.');
    }

    /**
     * Удаление записи и связанных файлов
     */
    public function destroy(string $id)
    {
        $work = Works::findOrFail($id);

        // Spatie автоматически удалит связанные файлы с диска при удалении модели
        $work->delete();

        return redirect()->route('admin.works.index')
            ->with('success', 'Работа успешно удалена из базы данных.');
    }
}
