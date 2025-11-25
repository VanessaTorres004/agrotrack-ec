@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Mis Cultivos</h1>
        <a href="{{ route('cultivos.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> Nuevo Cultivo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($cultivos->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-seedling text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes cultivos registrados</h3>
            <p class="text-gray-500 mb-4">Comienza agregando tu primer cultivo</p>
            <a href="{{ route('cultivos.create') }}" 
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                Crear Cultivo
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cultivos as $cultivo)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $cultivo->nombre }}</h3>
                                <p class="text-sm text-gray-600">{{ $cultivo->finca->nombre }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($cultivo->estado == 'sembrado') bg-green-100 text-green-800
                                @elseif($cultivo->estado == 'desarrollo') bg-blue-100 text-blue-800
                                @elseif($cultivo->estado == 'cosechado') bg-yellow-100 text-yellow-800
                                @elseif($cultivo->estado == 'finalizado') bg-gray-100 text-gray-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($cultivo->estado) }}
                            </span>
                        </div>

                        @if($cultivo->variedad)
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-leaf mr-1"></i> {{ $cultivo->variedad }}
                            </p>
                        @endif

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-ruler-combined w-5 mr-2"></i>
                                <span>{{ number_format($cultivo->hectareas, 2) }} ha</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt w-5 mr-2"></i>
                                <span>{{ $cultivo->fecha_siembra->format('d/m/Y') }}</span>
                            </div>
                            @if($cultivo->fecha_cosecha_estimada)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar-check w-5 mr-2"></i>
                                    <span>Cosecha: {{ $cultivo->fecha_cosecha_estimada->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- IDC Indicator -->
                        @if($cultivo->indicadores->isNotEmpty())
                            <div class="mb-4 p-3 rounded-lg
                                @php
                                    $idc = $cultivo->idc_actual;
                                    $clasificacion = $cultivo->clasificacion_actual;
                                @endphp
                                @if($clasificacion == 'excelente') bg-green-50 border border-green-200
                                @elseif($clasificacion == 'bueno') bg-blue-50 border border-blue-200
                                @elseif($clasificacion == 'regular') bg-yellow-50 border border-yellow-200
                                @elseif($clasificacion == 'malo') bg-orange-50 border border-orange-200
                                @elseif($clasificacion == 'critico') bg-red-50 border border-red-200
                                @else bg-gray-50 border border-gray-200
                                @endif">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-600">IDC</span>
                                    <span class="text-lg font-bold
                                        @if($clasificacion == 'excelente') text-green-700
                                        @elseif($clasificacion == 'bueno') text-blue-700
                                        @elseif($clasificacion == 'regular') text-yellow-700
                                        @elseif($clasificacion == 'malo') text-orange-700
                                        @elseif($clasificacion == 'critico') text-red-700
                                        @else text-gray-700
                                        @endif">
                                        {{ number_format($idc, 2) }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-600 mt-1 capitalize">{{ str_replace('_', ' ', $clasificacion) }}</div>
                            </div>
                        @endif

                        <!-- Alertas -->
                        @if($cultivo->alertas->where('estado', 'activa')->isNotEmpty())
                            <div class="mb-4">
                                <div class="flex items-center text-xs text-red-600">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <span>{{ $cultivo->alertas->where('estado', 'activa')->count() }} alerta(s) activa(s)</span>
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-2 pt-4 border-t">
                            <a href="{{ route('cultivos.show', $cultivo) }}" 
                               class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                                Ver Detalles
                            </a>
                            <a href="{{ route('cultivos.edit', $cultivo) }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg transition text-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection