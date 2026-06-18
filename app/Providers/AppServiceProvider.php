<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Promotion;
use App\Models\Specialist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.booking-modal', function ($view) {
            $view->with([
                'categories' => Category::with(['services' => fn($q) => $q->where('active', true)])->get(),
                'promotions' => Promotion::whereHas('service', fn($q) => $q->where('active', true))->with('service')->get(),
                'team' => Specialist::with('user')->get(),
            ]);
        });
        Validator::replacer('required', function ($message, $attribute, $rule, $parameters) {
            $attributes = [
                'first_name' => 'Имя',
                'last_name' => 'Фамилия',
                'phone' => 'Телефон',
                'email' => 'Email',
                'password' => 'Пароль',
            ];

            $attributeName = $attributes[$attribute] ?? $attribute;
            return str_replace(':attribute', $attributeName, 'Поле :attribute обязательно для заполнения');
        });

        Validator::replacer('email', function ($message, $attribute, $rule, $parameters) {
            return 'Введите корректный email адрес';
        });

        Validator::replacer('unique', function ($message, $attribute, $rule, $parameters) {
            if ($attribute === 'email') {
                return 'Этот email уже зарегистрирован в системе';
            }
            return $message;
        });

        Validator::replacer('confirmed', function ($message, $attribute, $rule, $parameters) {
            if ($attribute === 'password') {
                return 'Пароли не совпадают';
            }
            return $message;
        });

        Validator::replacer('min', function ($message, $attribute, $rule, $parameters) {
            if ($attribute === 'password') {
                return 'Пароль должен содержать минимум ' . $parameters[0] . ' символов';
            }
            return $message;
        });

    }
}
