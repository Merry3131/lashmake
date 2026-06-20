<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\SpecialistController;
use App\Http\Controllers\ExamplesOfWorkController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Middleware\MasterMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', MainPageController::class)->name('home');
// главная страница
Route::get('/promotions', PromotionController::class)->name('promotions.index');
// услуги
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
// специалисты
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
Route::get('/team/{team}', [TeamController::class, 'show'])->name('team.show');
// примеры работ
Route::get('/example_of_works', [ExamplesOfWorkController::class, 'index'])->name('works.index');
Route::get('/works/{works}', [ExamplesOfWorkController::class, 'show'])->name('work.show');
// отзывы
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/notifications/clear', [App\Http\Controllers\ProfileController::class, 'clearNotifications'])->name('notifications.clear');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [ProfileController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');




});
Route::post('/api/appointments', [WorkScheduleController::class, 'store']);

Route::middleware(MasterMiddleware::class)->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
});

//роуты админа
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    //главная страница админки
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    //управление услугами, мастерами, записями
//    Route::resource('services', AdminServicesController::class);
    // crud таблицы Категории Услуг
    Route::resource('categories', CategoryController::class);
    // crud таблицы Специалисты
    Route::resource('specialists', SpecialistController::class);
    // crud таблицы Специалисты
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    // промежуточный шаг для сборки мастера
    Route::get('specialists/build/{user}', [SpecialistController::class, 'build'])->name('specialists.build');
    Route::resource('specialists', SpecialistController::class);
    // записи
    Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class);
    // акции
    Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
    Route::get('/promotions/get-price', [App\Http\Controllers\Admin\PromotionController::class, 'getPrice'])->name('promotions.get-price');
    Route::resource('works', \App\Http\Controllers\Admin\ExampleOfWorkController::class);
    // график работы
    Route::resource('/schedule', \App\Http\Controllers\Admin\WorkScheduleController::class);
    Route::get('/services/print', [App\Http\Controllers\Admin\ServiceController::class, 'print'])->name('services.print');
    // модерация отзывов
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'update', 'destroy']);
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => bcrypt($password)])->save();
        }
    );
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

require __DIR__.'/auth.php';
