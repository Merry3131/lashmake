<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Specialist;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {

        $team = Specialist::with(['service_specialist.category', 'user', 'level', 'reviews'])->get();

        return view('public.team', compact('team'));
    }

    public function show(Specialist $team)
    {
        return view('public.team-show', compact('team'));
    }
}
