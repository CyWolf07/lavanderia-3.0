<?php

namespace App\Http\Controllers;

use App\Models\Prenda;
use App\Models\Produccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduccionController extends Controller
{
    public function index()
    {
        // View for user's own production
        $userId = Auth::id() ?? (\App\Models\User::first()?->id ?? 1);
        $producciones = Produccion::with('prenda')->where('user_id', $userId)->latest()->get();
        $prendas = Prenda::all();
        return view('produccion.index', compact('producciones', 'prendas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prenda_id' => 'required|exists:prendas,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $prenda = Prenda::findOrFail($request->prenda_id);
        $total = $prenda->precio * $request->cantidad;

        Produccion::create([
            'user_id' => Auth::id() ?? (\App\Models\User::first()?->id ?? 1),
            'prenda_id' => $request->prenda_id,
            'cantidad' => $request->cantidad,
            'total' => $total,
        ]);

        return redirect()->back()->with('success', 'Producción registrada exitosamente.');
    }
}
