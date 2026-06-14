<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        // Подгружаем отзывы вместе с мастерами, чтобы избежать N+1 запросов
        $team = Specialist::with(['service_specialist.category', 'user', 'level', 'reviews'])->get();

        return view('public.team', compact('team'));
    }
}
