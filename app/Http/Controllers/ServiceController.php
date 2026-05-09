<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(){
        //категории с услугами
        $categories = Category::with(['services' => function ($query) {
            $query->where('active', true);
        }])->get();

        $promotions = Promotion::whereHas('service', function ($query) {
            $query->where('active', true);
        })->with('service')->get();
        return view('public.services', compact('categories', 'promotions'));

    }
}
