<?php

namespace App\Http\Controllers;

use App\Models\Prenda;
use Illuminate\Http\Request;

class PrendaController extends Controller
{
    public function index()
    {
        $prendas = Prenda::all();
        return view('prendas.index', compact('prendas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        Prenda::create($request->all());

        return redirect()->route('prendas.index')->with('success', 'Prenda agregada correctamente.');
    }

    public function update(Request $request, Prenda $prenda)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $prenda->update($request->all());

        return redirect()->route('prendas.index')->with('success', 'Prenda actualizada correctamente.');
    }

    public function destroy(Prenda $prenda)
    {
        $prenda->delete();
        return redirect()->route('prendas.index')->with('success', 'Prenda eliminada correctamente.');
    }
}
