@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🌤️ Nuevo Registro Climático</h1>
            <p class="mt-2 text-gray-600">Registrar condiciones meteorológicas</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('clima.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Finca -->
                    <div>
                        <label for="finca_id" class="block text-sm font-medium text-gray-700 mb-2">Finca</label>
                        <select id="finca_id" name="finca_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Seleccionar finca</option>
                            @foreach($fincas as $finca)
                                <option value="{{ $finca->id }}" {{ old('finca_id') == $finca->id ? 'selected' : '' }}>
                                    {{ $finca->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('finca_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input type="date" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('fecha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Temperaturas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="temperatura_min" class="block text-sm font-medium text-gray-700 mb-2">Temperatura Mínima (°C)</label>
                            <input type="number" id="temperatura_min" name="temperatura_min" step="0.1" value="{{ old('temperatura_min') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('temperatura_min')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="temperatura_max" class="block text-sm font-medium text-gray-700 mb-2">Temperatura Máxima (°C)</label>
                            <input type="number" id="temperatura_max" name="temperatura_max" step="0.1" value="{{ old('temperatura_max') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('temperatura_max')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Precipitación y Humedad -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="precipitacion_mm" class="block text-sm font-medium text-gray-700 mb-2">Precipitación (mm)</label>
                            <input type="number" id="precipitacion_mm" name="precipitacion_mm" step="0.1" min="0" value="{{ old('precipitacion_mm', 0) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('precipitacion_mm')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="humedad_relativa" class="block text-sm font-medium text-gray-700 mb-2">Humedad Relativa (%)</label>
                            <input type="number" id="humedad_relativa" name="humedad_relativa" step="0.1" min="0" max="100" value="{{ old('humedad_relativa') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('humedad_relativa')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{ route('clima.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                            Guardar Registro
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
