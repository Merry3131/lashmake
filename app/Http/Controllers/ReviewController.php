<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(){
        $reviews = Review::with(['user', 'specialist.user', 'appointment.service'])->latest()->get();

        return view('public.reviews', compact('reviews'));
    }
}
