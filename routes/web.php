<?php

use App\Http\Controllers\ExamplesOfWorkController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\WorkScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', MainPageController::class)->name('home');
// главная страница
Route::get('/promotions', PromotionController::class)->name('promotions.index');
// услуги
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
// специалисты
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
// примеры работ
Route::get('/example_of_works', [ExamplesOfWorkController::class, 'index'])->name('works.index');
// отзывы
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [ProfileController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/api/appointments', [WorkScheduleController::class, 'store']);
});

//роуты админа
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    //главная страница админки
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    //управление услугами, мастерами, записями
    Route::resource('services', AdminServicesController::class);
});

require __DIR__.'/auth.php';
