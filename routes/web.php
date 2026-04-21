<?php

use App\Http\Controllers\ExamplesOfWorkController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', MainPageController::class)->name('home');
Route::get('/promotions', PromotionController::class)->name('promotions.index');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
Route::get('/example_of_works', [ExamplesOfWorkController::class, 'index'])->name('works.index');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
