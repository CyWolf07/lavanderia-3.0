<?php

namespace App\Http\Controllers;

use App\Models\Prenda;
use App\Models\Produccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduccionController extends Controller
{
    private function resolveValidUserId(): ?int
    {
        return Auth::id() ?? \App\Models\User::query()->value('id');
    }

    public function index()
    {
        // Show latest production records available in the system.
        $producciones = Produccion::with('prenda')->latest()->limit(20)->get();
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
        $userId = $this->resolveValidUserId();
        if (! $userId) {
            return redirect()->back()->with('error', 'No existe un usuario valido para registrar la producción.');
        }

        Produccion::create([
            'user_id' => $userId,
            'prenda_id' => $request->prenda_id,
            'cantidad' => $request->cantidad,
            'total' => $total,
        ]);

        return redirect()->back()->with('success', 'Producción registrada exitosamente.');
    }
}
