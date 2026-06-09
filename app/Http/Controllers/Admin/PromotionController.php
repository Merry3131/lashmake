<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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
        $promotions = Promotion::with(['service', 'specialist.user'])->latest()->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::where('active', 1)->get();
        // Подгружаем пользователей мастеров, чтобы в выпадающем списке видеть их имена
        $specialists = Specialist::with('user')->get();

        return view('admin.promotions.create', compact('services', 'specialists'));
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
        $specialists = Specialist::with('user')->get();

        return view('admin.promotions.edit', compact('promotion', 'services', 'specialists'));
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
