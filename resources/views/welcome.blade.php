@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden bg-white rounded-2xl shadow-xl flex items-center h-[500px]">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-100 opacity-90"></div>
    <!-- Interactive Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10"></div>
    
    <div class="relative z-10 w-full px-8 md:px-16 text-center lg:text-left flex flex-col lg:flex-row items-center justify-between">
        <div class="lg:w-1/2 space-y-6">
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight">
                Gestión Inteligente de <span class="text-primary">Lavandería Exclusiva</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-xl mx-auto lg:mx-0">
                Lleva el control de tus prendas, producción y reportes con una interfaz moderna, rápida y segura en la web.
            </p>
            <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-4">
                <a href="{{ route('produccion.index') }}" class="px-8 py-3 bg-primary text-white font-semibold rounded-lg shadow-md hover:bg-secondary hover:shadow-lg transition transform hover:-translate-y-1">
                    Registrar Producción
                </a>
                <a href="{{ route('prendas.index') }}" class="px-8 py-3 bg-white text-primary border border-gray-200 font-semibold rounded-lg shadow-sm hover:bg-gray-50 transition transform hover:-translate-y-1">
                    Gestionar Prendas
                </a>
            </div>
        </div>
        <!-- Right side graphic placeholder -->
        <div class="lg:w-1/2 mt-10 lg:mt-0 relative hidden md:block">
            <div class="w-72 h-72 lg:w-96 lg:h-96 bg-gradient-to-tr from-indigo-300 to-primary rounded-full blur-3xl opacity-30 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 animate-pulse"></div>
            <div class="relative bg-white/60 backdrop-blur-md border border-white/50 shadow-2xl rounded-2xl p-8 max-w-sm mx-auto transform rotate-3 hover:rotate-0 transition duration-500">
                <div class="flex items-center justify-between border-b pb-4 border-gray-200">
                    <h3 class="font-bold text-gray-800">Estadísticas Rápidas</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">+12% hoy</span>
                </div>
                <div class="mt-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Prendas Procesadas</span>
                        <span class="font-bold text-gray-900">145</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full w-[70%]"></div>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-sm text-gray-500">Ingresos Totales</span>
                        <span class="font-bold text-gray-900">$ 840.500</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
