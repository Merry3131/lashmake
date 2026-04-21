<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $categories = Category::with('services')->get();
        $promotions = Promotion::all();
        return view('public.services', compact('categories', 'promotions'));
    }
}
