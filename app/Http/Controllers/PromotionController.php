<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function __invoke(Request $request){
        $promotions = Promotion::all();
        return view('public.promotions', compact('promotions'));
    }

}
