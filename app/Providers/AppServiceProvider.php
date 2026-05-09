<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Promotion;
use App\Models\Specialist;
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
    }
}
