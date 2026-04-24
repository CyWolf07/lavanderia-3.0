<?php

namespace App\Http\Controllers;

use App\Models\HistorialProduccion;
use App\Models\Prenda;
use App\Models\Produccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'prenda_id' => ['required', 'exists:prendas,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
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

        return redirect()->route('produccion.index')->with('success', 'Produccion registrada correctamente.');
    }

    public function cerrar()
    {
        $producciones = Produccion::with(['user', 'prenda'])
            ->orderBy('user_id')
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        if ($producciones->isEmpty()) {
            return redirect()->route('admin.dashboard')->with('error', 'No hay registros activos para cerrar.');
        }

        $fechaBase = Carbon::parse(optional($producciones->sortByDesc('fecha')->first())->fecha ?? now());
        $periodo = HistorialProduccion::periodoDesdeFecha($fechaBase);

        DB::transaction(function () use ($producciones, $periodo) {
            foreach ($producciones as $produccion) {
                HistorialProduccion::create([
                    'user_id' => $produccion->user_id,
                    'prenda_id' => $produccion->prenda_id,
                    'prenda_nombre' => $produccion->prenda->nombre ?? 'Prenda eliminada',
                    'precio_unitario' => $produccion->cantidad > 0 ? ($produccion->total / $produccion->cantidad) : 0,
                    'cantidad' => $produccion->cantidad,
                    'total' => $produccion->total,
                    'fecha' => optional($produccion->fecha)->toDateString() ?? now()->toDateString(),
                    'periodo' => $periodo['periodo'],
                    'anio' => $periodo['anio'],
                    'mes' => $periodo['mes'],
                    'quincena' => $periodo['quincena'],
                    'cerrado_por' => Auth::id(),
                ]);
            }

            Produccion::query()->delete();
        });

        return redirect()->route('admin.reportes.periodo', [
            'periodo' => $periodo['periodo'],
            'imprimir' => 1,
        ])->with('success', 'Quincena cerrada, respaldada e informe listo para imprimir.');
    }

    public function reportePeriodo(string $periodo)
    {
        $registros = HistorialProduccion::with('user')
            ->where('periodo', $periodo)
            ->orderBy('user_id')
            ->orderBy('fecha')
            ->orderBy('id')
            ->get();

        abort_if($registros->isEmpty(), 404);

        return view('admin.reporte-periodo', [
            'periodo' => $periodo,
            'registrosPorUsuario' => $registros->groupBy('user_id'),
            'totalGeneral' => $registros->sum('total'),
            'totalPrendas' => $registros->sum('cantidad'),
            'autoPrint' => request()->boolean('imprimir'),
        ]);
    }
}
