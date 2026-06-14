<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\LevelService;
use App\Models\Promotion;
use App\Models\Service;
use App\Models\Specialist;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::with(['service', 'specialist.level', 'specialist.user'])
            ->get()
            ->map(function ($promotion) {
                // Добавляем вычисляемое поле
                $promotion->price_display = $promotion->price_display;
                return $promotion;
            });

        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::where('active', 1)->get();
        $specialists = Specialist::with('user', 'level')->get();

        // Формируем массив с ценами для каждой услуги
        $priceData = [];
        foreach ($services as $service) {
            $levelServices = LevelService::where('service_id', $service->id)->with('level')->get();

            // Данные для конкретных мастеров
            $specialistsData = [];
            foreach ($specialists as $specialist) {
                if ($specialist->level_id) {
                    $ls = $levelServices->firstWhere('level_id', $specialist->level_id);
                    $specialistsData[$specialist->id] = $ls ? [
                        'price' => $ls->price,
                        'duration' => $ls->duration,
                    ] : null;
                } else {
                    $specialistsData[$specialist->id] = null;
                }
            }

            // Данные для случая "Все мастера" (диапазон)
            $minPrice = $levelServices->min('price');
            $maxPrice = $levelServices->max('price');
            $priceData[$service->id] = [
                'specialists' => $specialistsData,
                'all' => [
                    'is_range' => true,
                    'price_range' => ($minPrice == $maxPrice)
                        ? number_format($minPrice, 0, '.', ' ') . ' ₽'
                        : number_format($minPrice, 0, '.', ' ') . ' – ' . number_format($maxPrice, 0, '.', ' ') . ' ₽',
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                ],
            ];
        }

        return view('admin.promotions.create', compact('services', 'specialists', 'priceData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'service_id'       => 'required|exists:services,id',
            'specialist_id'    => 'nullable|exists:specialists,id', // null, если акция на услугу у ВСЕХ мастеров
            'discount_percent' => 'required|integer|min:1|max:100',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ]);

        // Поле type не трогаем, пишем заглушку 'discount', как в структуре таблицы
        $validated['type'] = 'discount';

        Promotion::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция успешно создана!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promotion)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        $services = Service::where('active', 1)->get();
        $specialists = Specialist::with('user', 'level')->get();

        $priceData = [];
        foreach ($services as $service) {
            $levelServices = LevelService::where('service_id', $service->id)->with('level')->get();

            $specialistsData = [];
            foreach ($specialists as $specialist) {
                if ($specialist->level_id) {
                    $ls = $levelServices->firstWhere('level_id', $specialist->level_id);
                    $specialistsData[$specialist->id] = $ls ? [
                        'price' => $ls->price,
                        'duration' => $ls->duration,
                    ] : null;
                } else {
                    $specialistsData[$specialist->id] = null;
                }
            }

            $minPrice = $levelServices->min('price');
            $maxPrice = $levelServices->max('price');
            $priceData[$service->id] = [
                'specialists' => $specialistsData,
                'all' => [
                    'is_range' => true,
                    'price_range' => ($minPrice == $maxPrice)
                        ? number_format($minPrice, 0, '.', ' ') . ' ₽'
                        : number_format($minPrice, 0, '.', ' ') . ' – ' . number_format($maxPrice, 0, '.', ' ') . ' ₽',
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                ],
            ];
        }

        return view('admin.promotions.edit', compact('promotion', 'services', 'specialists', 'priceData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'service_id'       => 'required|exists:services,id',
            'specialist_id'    => 'nullable|exists:specialists,id',
            'discount_percent' => 'required|integer|min:1|max:100',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ]);

        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Акция удалена.');
    }
}
