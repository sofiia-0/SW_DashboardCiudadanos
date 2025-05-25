<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Citizen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function index()
    {
        $totalCiudades = City::count();
        $totalCiudadanos = City::withCount('citizens')->get()->sum('citizens_count');
        $ciudadanosPorCiudad = City::withCount('citizens')->orderBy('name')->get();

        $ciudades = City::with(['citizens' => function ($query) {
            $query->orderBy('first_name')->orderBy('last_name');
        }])->orderBy('name')->get();

        return view('dashboard', compact('totalCiudades', 'totalCiudadanos', 'ciudadanosPorCiudad', 'ciudades'));
    }

    
}
