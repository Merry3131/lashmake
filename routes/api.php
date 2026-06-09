<?php


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
