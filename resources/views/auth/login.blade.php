@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-1">Iniciar sesión</h2>
    <p class="text-sm text-gray-500 mb-6">Ingresa a tu cuenta para gestionar la lavandería.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
        @csrf
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
        <button type="submit"
            class="w-full bg-gradient-to-r from-primary to-indigo-600 text-white font-medium py-2.5 px-4 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition">
            Entrar
        </button>
    </form>

    <p class="mt-5 text-sm text-gray-600">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Crear usuario</a>
    </p>
</div>
@endsection
