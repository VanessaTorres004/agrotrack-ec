@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🌤️ Detalle del Registro Climático</h1>
                <p class="mt-2 text-gray-600">{{ $clima->fecha->format('d/m/Y') }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('clima.edit', $clima) }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                    Editar
                </a>
                <a href="{{ route('clima.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    Volver
                </a>
            </div>
        </div>

        <!-- Información del Registro -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Información General</h2>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Finca</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $clima->finca->nombre }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Fecha</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $clima->fecha->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Temperatura Mínima</label>
                        <p class="mt-1 text-lg text-gray-900">{{ number_format($clima->temperatura_min, 1) }}°C</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Temperatura Máxima</label>
                        <p class="mt-1 text-lg text-gray-900">{{ number_format($clima->temperatura_max, 1) }}°C</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Precipitación</label>
                        <p class="mt-1 text-lg text-gray-900">{{ number_format($clima->precipitacion_mm ?? $clima->precipitacion ?? 0, 1) }} mm</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Humedad Relativa</label>
                        <p class="mt-1 text-lg text-gray-900">{{ number_format($clima->humedad_relativa ?? $clima->humedad ?? 0, 1) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-6 flex justify-end">
            <form action="{{ route('clima.destroy', $clima) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    Eliminar Registro
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
