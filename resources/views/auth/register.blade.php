@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-1">Crear cuenta</h2>
    <p class="text-sm text-gray-500 mb-6">Registra un usuario para acceder al sistema.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc ml-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
            <input id="password" name="password" type="password" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <button type="submit"
            class="w-full bg-gradient-to-r from-primary to-indigo-600 text-white font-medium py-2.5 px-4 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition">
            Crear cuenta
        </button>
    </form>

    <p class="mt-5 text-sm text-gray-600">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Iniciar sesión</a>
    </p>
</div>
@endsection
