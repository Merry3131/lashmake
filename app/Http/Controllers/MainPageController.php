<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Promotion;
use App\Models\Specialist;
use App\Models\Works;
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
        $specialists = Specialist::all();
        $works = Works::all();
        return view('public.index', compact('categories', 'promotions', 'specialists', 'works'));
    }
}
