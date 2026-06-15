<?php


use App\Http\Controllers\BookingController;
use App\Http\Controllers\WorkScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// расчет свободного времемени для записи
Route::get('/slots', [WorkScheduleController::class, 'getAvailableSlots']);

// сохраняем запись клиента
Route::post('/appointments', [WorkScheduleController::class, 'store']);

Route::get('/level-service-info', [BookingController::class, 'getLevelServiceInfo']);

Route::get('/specialist/{id}', [BookingController::class, 'getSpecialist']);

Route::get('/specialist/{specialist}/services', function ($specialistId) {
    $specialist = \App\Models\Specialist::with('service_specialist')->findOrFail($specialistId);
    return response()->json($specialist->service_specialist);
});
