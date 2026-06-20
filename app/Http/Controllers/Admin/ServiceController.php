<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Level;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function print()
    {
        return response('Работает!');
    }

    public function index()
    {
        $services = Service::orderBy('name', 'asc')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        $levels = Level::all();
        return view('admin.services.create', compact('categories', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name',
            'description' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'active' => 'required|boolean',
            'prices' => 'nullable|array',
            'prices.*.price' => 'nullable|numeric|min:0|max:99999999.99',
            'prices.*.duration' => 'nullable|integer|min:1|max:65535',
        ], [
            'name.required' => 'Укажите название услуги.',
            'name.unique' => 'Такая услуга уже существует.',
            'description.required' => 'Укажите описание услуги.',
            'category_id.required' => "Выберите категорию услуги.",
            'category_id.exists' => 'Выбранная категория не существует.',
            'prices.*.price.min' => 'Цена не может быть отрицательной.',
            'prices.*.price.max' => 'Цена не может превышать 99 999 999.99 ₽.',
            'prices.*.duration.min' => 'Длительность должна быть не менее 1 минуты.',
            'prices.*.duration.max' => 'Длительность не может превышать 65535 минут (примерно 45.5 дней).',
        ]);

        // Создаём услугу
        $service = Service::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'active' => $validated['active'],
        ]);

        // Сохраняем цены для разных уровней
        if (!empty($validated['prices'])) {
            foreach ($validated['prices'] as $levelId => $priceData) {
                if (!empty($priceData['price']) || !empty($priceData['duration'])) {
                    $service->levels()->attach($levelId, [
                        'price' => $priceData['price'] ?? 0,
                        'duration' => $priceData['duration'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Услуга добавлена.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Service $service)
    {
        $categories = Category::select('id', 'display_name')->get();
        $levels = Level::all();

        $service->load('levels');

        return view('admin.services.edit', compact('service', 'categories', 'levels'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name,' . $service->id,
            'description' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'active' => 'required|boolean',
            'prices' => 'nullable|array',
            'prices.*.price' => 'nullable|numeric|min:0|max:99999999.99',
            'prices.*.duration' => 'nullable|integer|min:1|max:65535',
        ], [
            'name.required' => 'Укажите название услуги.',
            'name.unique' => 'Такая услуга уже существует.',
            'description.required' => 'Укажите описание услуги.',
            'category_id.required' => "Выберите категорию услуги.",
            'category_id.exists' => 'Выбранная категория не существует.',
            'prices.*.price.min' => 'Цена не может быть отрицательной.',
            'prices.*.price.max' => 'Цена не может превышать 99 999 999.99 ₽.',
            'prices.*.duration.min' => 'Длительность должна быть не менее 1 минуты.',
            'prices.*.duration.max' => 'Длительность не может превышать 65535 минут (примерно 45.5 дней).',
        ]);

        // Обновляем услугу
        $service->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'active' => $validated['active'],
        ]);

        // Подготавливаем данные для синхронизации
        $syncData = [];
        if (!empty($validated['prices'])) {
            foreach ($validated['prices'] as $levelId => $priceData) {
                $syncData[$levelId] = [
                    'price' => $priceData['price'] ?? 0,
                    'duration' => $priceData['duration'] ?? 0,
                ];
            }
        }

        // Синхронизируем уровни с pivot данными
        $service->levels()->sync($syncData);

        return redirect()->route('admin.services.index')->with('success', 'Услуга обновлена.');
    }

    public function destroy(Service $service)
    {
        $service->levels()->detach();
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Услуга удалена.');
    }
}
