<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(){
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $totalClients = User::where('role', 'client')->count();

        return view('admin.dashboard', compact('totalAppointments', 'pendingAppointments', 'totalClients'));
    }
}
