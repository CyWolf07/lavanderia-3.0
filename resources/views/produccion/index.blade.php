@extends('layouts.app')

@section('content')
<div x-data="produccionForm()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Registro de Producción</h2>
            <p class="text-gray-500 mt-1">Lleva el conteo diario del trabajo realizado.</p>
        </div>
    </div>

    @if(session('success'))
    <!-- Custom interactive alert -->
    <div x-data="{ show: true }" x-show="show" class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-md shadow-sm relative">
        <div class="flex">
            <svg class="h-5 w-5 text-indigo-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="text-sm text-indigo-700 ml-3">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="absolute top-4 right-4 text-indigo-500 hover:text-indigo-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
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
                </div>

                <div class="bg-indigo-50 rounded-xl p-4 mt-2">
                    <p class="text-xs text-indigo-800 uppercase font-semibold">Total Calculado</p>
                    <p class="text-2xl font-bold text-primary mt-1">$ <span x-text="total.toLocaleString('es-CO')">0</span></p>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary to-indigo-600 text-white font-medium py-3 px-4 rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transition transform hover:-translate-y-0.5">
                    Guardar Producción
                </button>
            </form>
        </div>

        <!-- Historial -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Tus Últimos Registros</h3>
            </div>
            <div class="p-0">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Prenda</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Cant.</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ingreso Generado</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($producciones as $prod)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $prod->prenda->nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $prod->prenda->tipo ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700 bg-gray-50">{{ $prod->cantidad }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">+$ {{ number_format($prod->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">{{ $prod->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-2">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p>No has registrado producción todavía.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                if(!select) return;
                const option = select.options[select.selectedIndex];
                const precio = parseFloat(option ? option.getAttribute('data-precio') : 0) || 0;
                this.total = precio * this.cantidad;
            }
        }
    }
</script>
@endsection
