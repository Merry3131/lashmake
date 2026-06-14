<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(){
        $categories = Category::with([
            'services' => function ($query) {
                $query->where('active', true);
            },
            'services.levels', // Загружаем цены и время (pivot)
            'services.specialists.user', // Загружаем мастеров и их личные данные
            'services.specialists.level' // Загружаем уровни мастеров
        ])->get();

        $promotions = Promotion::whereHas('service', function ($query) {
            $query->where('active', true);
        })->with(['service.levels', 'service.specialists.user', 'service.specialists.level'])->get();

        return view('public.services', compact('categories', 'promotions'));

    }

    public function show(Service $service)
    {
        return view('public.services-show', compact('service'));
    }
}
