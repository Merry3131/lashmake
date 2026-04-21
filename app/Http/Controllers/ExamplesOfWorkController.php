<?php

namespace App\Http\Controllers;

use App\Models\Works;
use Illuminate\Http\Request;

class ExamplesOfWorkController extends Controller
{
    public function index(){
        $works = Works::with(['service.category', 'specialist.user'])->latest()->get();
        return view('public.example_of_works', compact('works'));
    }

}
