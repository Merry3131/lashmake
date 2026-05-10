<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        // все мастера с данными из таблицы users
        $team = Specialist::with(['services.category', 'user'])->get();

        return view('public.team', compact('team'));
    }
}
