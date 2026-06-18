<?php

namespace App\Http\Controllers;

use App\Models\LevelService;
use App\Models\Specialist;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getLevelServiceInfo(Request $request)
    {
        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'discount' => 'nullable|integer|min:0|max:100',
        ]);

        $specialist = Specialist::findOrFail($request->specialist_id);
        $levelId = $specialist->level_id;

        $levelService = LevelService::where('service_id', $request->service_id)
            ->where('level_id', $levelId)
            ->first();

        if (!$levelService) {
            return response()->json(['error' => 'Цена для данной услуги и уровня не найдена'], 404);
        }

        $price = $levelService->price;
        $duration = $levelService->duration;

        if ($request->has('discount') && $request->discount > 0) {
            $price = $price * (1 - $request->discount / 100);
            $price = round($price, 2);
        }

        return response()->json([
            'price' => number_format($price, 0, '.', ' ') . ' ₽',
            'duration' => $duration,
        ]);
    }

    public function getSpecialistsByService($serviceId)
    {
        $specialists = \App\Models\Specialist::whereHas('service_specialist', function($q) use ($serviceId) {
            $q->where('service_id', $serviceId);
        })->with('user')->get();

        return response()->json($specialists);
    }

    public function getSpecialist($id)
    {
        $specialist = Specialist::with('user', 'level')->findOrFail($id);
        return response()->json([
            'name' => $specialist->user->first_name . ' ' . $specialist->user->last_name,
            'level' => $specialist->level->name ?? '',
            'bio' => $specialist->bio ?? '',
        ]);
    }
}
