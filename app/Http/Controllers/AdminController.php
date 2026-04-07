<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produccion;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // To be used if user has rol_id = 1 (Admin)
        $totalUsuarios = User::count();
        $totalProducciones = Produccion::count();
        $ingresosTotales = Produccion::sum('total');

        $ultimasProducciones = Produccion::with(['user', 'prenda'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('totalUsuarios', 'totalProducciones', 'ingresosTotales', 'ultimasProducciones'));
    }
}
