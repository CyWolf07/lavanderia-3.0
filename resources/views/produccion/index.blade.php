@extends('layouts.app')

@section('title', 'Produccion')

@section('content')
@php($esUsuario = $user->tieneRol('usuario'))
<div x-data="produccionForm()" class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.35em] text-sky-700">Produccion</p>
            <h1 class="mt-2 text-3xl font-black text-slate-900">Registro personal de {{ $user->name }}</h1>
            <p class="mt-2 text-sm text-slate-500">
                @if ($user->tieneRol('usuario'))
                    Solo puedes registrar produccion y consultar tus pagos por quincena.
                @else
                    Tambien puedes ir al panel administrativo para gestionar usuarios, prendas y cierres.
                @endif
            </p>
        </div>
        @if ($user->tieneRol('admin', 'programador'))
            <a href="{{ route('admin.dashboard') }}" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                Ir al panel admin
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm relative">
        <div class="flex">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V6a1 1 0 112 0v3a1 1 0 11-2 0zm0 4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd" /></svg>
            <p class="text-sm text-red-700 ml-3">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="absolute top-4 right-4 text-red-500 hover:text-red-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Formulario -->
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-xl border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-gradient-to-br from-indigo-100 to-white rounded-full opacity-50"></div>
            
            <h3 class="text-lg font-bold text-gray-800 border-b pb-4 mb-4 relative z-10">Nuevo Registro</h4>
            <form action="{{ route('produccion.store') }}" method="POST" class="space-y-5 relative z-10">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prenda Procesada</label>
                    <select name="prenda_id" x-model="selectedPrendaId" @change="updatePrice()" required class="block w-full border border-gray-300 bg-white rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm transition">
                        <option value="">-- Selecciona --</option>
                        @foreach($prendas as $prenda)
                            <option value="{{ $prenda->id }}" data-precio="{{ $prenda->precio }}">{{ $prenda->nombre }} ({{$prenda->tipo??'Gnl'}}) - $ {{ number_format($prenda->precio, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                    <div class="flex items-center">
                        <button type="button" @click="if(cantidad > 1) { cantidad--; } updatePrice()" class="bg-gray-100 text-gray-600 hover:bg-gray-200 p-2 rounded-l-md border border-gray-300 border-r-0 transition">-</button>
                        <input type="number" name="cantidad" x-model="cantidad" @input="updatePrice()" min="1" required class="block w-full border border-gray-300 text-center py-2 focus:outline-none sm:text-sm">
                        <button type="button" @click="cantidad++; updatePrice()" class="bg-gray-100 text-gray-600 hover:bg-gray-200 p-2 rounded-r-md border border-gray-300 border-l-0 transition">+</button>
                    </div>

                    <div>
                        <label for="cantidad" class="mb-2 block text-sm font-semibold text-slate-700">Cantidad</label>
                        <input id="cantidad" type="number" name="cantidad" x-model="cantidad" @input="updatePrice()" min="1" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200" required>
                    </div>

                    @unless($esUsuario)
                        <div class="rounded-2xl bg-slate-900 px-4 py-4 text-white">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-300">Total estimado</p>
                            <p class="mt-2 text-3xl font-black">$ <span x-text="total.toLocaleString('es-CO')">0</span></p>
                        </div>
                    @else
                        <div class="rounded-2xl bg-slate-100 px-4 py-4 text-slate-900">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Cantidad del registro</p>
                            <p class="mt-2 text-3xl font-black" x-text="cantidad || 0">0</p>
                        </div>
                    @endunless

                    <button type="submit" class="w-full rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white hover:bg-sky-700">
                        Guardar produccion
                    </button>
                </form>
            </div>

            <div class="rounded-[1.75rem] bg-emerald-600 p-6 text-white shadow-xl">
                <p class="text-sm uppercase tracking-[0.25em] text-emerald-100">Quincena activa</p>
                <p class="mt-3 text-4xl font-black">$ {{ number_format($totalQuincena, 0, ',', '.') }}</p>
                <p class="mt-2 text-sm text-emerald-50">Este valor se reinicia visualmente al cerrar la quincena, pero queda guardado en historial.</p>
            </div>

            <div class="rounded-[1.75rem] bg-white p-6 shadow-xl ring-1 ring-slate-200">
                <h2 class="text-lg font-bold text-slate-900">Pagos por quincena</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($historialQuincenas as $periodo)
                        <div class="rounded-2xl border border-slate-200 px-4 py-3">
                            <p class="text-sm font-semibold text-slate-800">{{ $periodo->periodo }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $periodo->total_prendas }} prendas registradas</p>
                            <p class="mt-2 text-xl font-bold text-emerald-700">$ {{ number_format($periodo->total_periodo, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aun no tienes quincenas cerradas en historial.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="rounded-[1.75rem] bg-white shadow-xl ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">Registros actuales</h2>
                    <p class="mt-1 text-sm text-slate-500">Aqui ves solo la produccion activa antes del cierre de quincena.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Prenda</th>
                                <th class="px-6 py-4 font-semibold">Cantidad</th>
                                @unless($esUsuario)
                                    <th class="px-6 py-4 font-semibold">Total</th>
                                @endunless
                                <th class="px-6 py-4 font-semibold">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($producciones as $prod)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $prod->prenda->nombre ?? 'Sin prenda' }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $prod->cantidad }}</td>
                                    @unless($esUsuario)
                                        <td class="px-6 py-4 font-semibold text-emerald-700">$ {{ number_format($prod->total, 0, ',', '.') }}</td>
                                    @endunless
                                    <td class="px-6 py-4 text-slate-500">{{ optional($prod->fecha)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $esUsuario ? 3 : 4 }}" class="px-6 py-8 text-center text-slate-500">No hay registros en la quincena activa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-[1.75rem] bg-white shadow-xl ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">{{ $esUsuario ? 'Prendas por dia' : 'Totales diarios' }}</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $esUsuario ? 'Aqui solo ves cuantas prendas registraste por dia dentro de la quincena activa.' : 'La tabla resume el ingreso total de cada dia dentro de la quincena activa.' }}
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Dia</th>
                                <th class="px-6 py-4 font-semibold">{{ $esUsuario ? 'Prendas registradas' : 'Ingreso total' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($porDia as $d)
                                <tr>
                                    <td class="px-6 py-4 text-slate-700">{{ \Carbon\Carbon::parse($d->dia)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-900">
                                        @if ($esUsuario)
                                            {{ $d->total_prendas }}
                                        @else
                                            $ {{ number_format($d->total, 0, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-slate-500">Todavia no hay resumen diario.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function produccionForm() {
    return {
        selectedPrendaId: '',
        cantidad: 1,
        total: 0,
        updatePrice() {
            const select = document.querySelector('select[name="prenda_id"]');
            if (!select) return;
            const option = select.options[select.selectedIndex];
            const precio = parseFloat(option ? option.getAttribute('data-precio') : 0) || 0;
            this.total = precio * this.cantidad;
        }
    }
}
</script>
@endsection
