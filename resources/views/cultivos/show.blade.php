@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('cultivos.index') }}" class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Cultivos
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header del Cultivo -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $cultivo->nombre }}</h1>
                <p class="text-gray-600">
                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $cultivo->finca->nombre }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('cultivos.edit', $cultivo) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('cultivos.destroy', $cultivo) }}" method="POST" 
                      onsubmit="return confirm('¿Estás seguro de eliminar este cultivo?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>

        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
            @if($cultivo->estado == 'sembrado') bg-green-100 text-green-800
            @elseif($cultivo->estado == 'desarrollo') bg-blue-100 text-blue-800
            @elseif($cultivo->estado == 'cosechado') bg-yellow-100 text-yellow-800
            @elseif($cultivo->estado == 'finalizado') bg-gray-100 text-gray-800
            @else bg-purple-100 text-purple-800
            @endif">
            {{ ucfirst($cultivo->estado) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información General -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Información General</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($cultivo->variedad)
                        <div>
                            <label class="text-sm text-gray-600 font-semibold">Variedad</label>
                            <p class="text-gray-800">{{ $cultivo->variedad }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Área</label>
                        <p class="text-gray-800">{{ number_format($cultivo->hectareas, 2) }} hectáreas</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Fecha de Siembra</label>
                        <p class="text-gray-800">{{ $cultivo->fecha_siembra->format('d/m/Y') }}</p>
                    </div>
                    @if($cultivo->fecha_cosecha_estimada)
                        <div>
                            <label class="text-sm text-gray-600 font-semibold">Cosecha Estimada</label>
                            <p class="text-gray-800">{{ $cultivo->fecha_cosecha_estimada->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
                @if($cultivo->notas)
                    <div class="mt-4">
                        <label class="text-sm text-gray-600 font-semibold">Notas</label>
                        <p class="text-gray-800 mt-1">{{ $cultivo->notas }}</p>
                    </div>
                @endif
            </div>

            <!-- Indicador IDC -->
            @if($cultivo->indicadores->isNotEmpty())
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Índice de Desempeño del Cultivo (IDC)</h2>
                        <form action="{{ route('cultivos.calcular-indicador', $cultivo) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition text-sm">
                                <i class="fas fa-sync mr-2"></i> Recalcular
                            </button>
                        </form>
                    </div>

                    @php
                        $indicadorActual = $cultivo->indicadores->first();
                        $idc = $indicadorActual->idc;
                        $clasificacion = $indicadorActual->clasificacion;
                    @endphp

                    <div class="text-center mb-6">
                        <div class="inline-block p-8 rounded-full
                            @if($clasificacion == 'excelente') bg-green-100
                            @elseif($clasificacion == 'bueno') bg-blue-100
                            @elseif($clasificacion == 'regular') bg-yellow-100
                            @elseif($clasificacion == 'malo') bg-orange-100
                            @elseif($clasificacion == 'critico') bg-red-100
                            @else bg-gray-100
                            @endif">
                            <div class="text-5xl font-bold
                                @if($clasificacion == 'excelente') text-green-700
                                @elseif($clasificacion == 'bueno') text-blue-700
                                @elseif($clasificacion == 'regular') text-yellow-700
                                @elseif($clasificacion == 'malo') text-orange-700
                                @elseif($clasificacion == 'critico') text-red-700
                                @else text-gray-700
                                @endif">
                                {{ number_format($idc, 2) }}
                            </div>
                            <div class="text-sm font-semibold text-gray-600 mt-2 uppercase">
                                {{ str_replace('_', ' ', $clasificacion) }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-800">{{ number_format($indicadorActual->rendimiento, 2) }}</div>
                            <div class="text-xs text-gray-600 mt-1">Rendimiento</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-800">{{ number_format($indicadorActual->calidad, 2) }}</div>
                            <div class="text-xs text-gray-600 mt-1">Calidad</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-800">{{ number_format($indicadorActual->eficiencia_costos, 2) }}</div>
                            <div class="text-xs text-gray-600 mt-1">Eficiencia</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-800">{{ number_format($indicadorActual->sostenibilidad, 2) }}</div>
                            <div class="text-xs text-gray-600 mt-1">Sostenibilidad</div>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        Última actualización: {{ $indicadorActual->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            @endif

            <!-- Actualizaciones Recientes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Actualizaciones</h2>
                    <a href="{{ route('actualizaciones.create', ['cultivo_id' => $cultivo->id]) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-plus mr-2"></i> Nueva
                    </a>
                </div>

                @if($cultivo->actualizaciones->isEmpty())
                    <p class="text-gray-500 text-center py-4">No hay actualizaciones registradas</p>
                @else
                    <div class="space-y-3">
                        @foreach($cultivo->actualizaciones->take(5) as $actualizacion)
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $actualizacion->tipo }}</div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $actualizacion->descripcion }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $actualizacion->fecha->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Cosechas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Cosechas</h2>
                    <a href="{{ route('cosechas.create', ['cultivo_id' => $cultivo->id]) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-plus mr-2"></i> Registrar
                    </a>
                </div>

                @if($cultivo->cosechas->isEmpty())
                    <p class="text-gray-500 text-center py-4">No hay cosechas registradas</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Fecha</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Cantidad</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Calidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cultivo->cosechas as $cosecha)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 text-sm">{{ $cosecha->fecha->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm text-right">{{ number_format($cosecha->cantidad, 2) }} {{ $cosecha->unidad }}</td>
                                        <td class="px-4 py-2 text-sm">{{ ucfirst($cosecha->calidad) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="space-y-6">
            <!-- Alertas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Alertas</h2>

                @php
                    $alertasActivas = $cultivo->alertas->where('estado', 'activa');
                @endphp

                @if($alertasActivas->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm">No hay alertas activas</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($alertasActivas as $alerta)
                            <div class="p-3 rounded-lg border-l-4
                                @if($alerta->tipo == 'critica') bg-red-50 border-red-500
                                @elseif($alerta->tipo == 'advertencia') bg-yellow-50 border-yellow-500
                                @else bg-blue-50 border-blue-500
                                @endif">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle mt-1 mr-2
                                        @if($alerta->tipo == 'critica') text-red-500
                                        @elseif($alerta->tipo == 'advertencia') text-yellow-500
                                        @else text-blue-500
                                        @endif"></i>
                                    <div class="flex-1">
                                        <div class="font-semibold text-sm text-gray-800">{{ ucfirst($alerta->tipo) }}</div>
                                        <p class="text-xs text-gray-600 mt-1">{{ $alerta->mensaje }}</p>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $alerta->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Resumen de Ventas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Ventas</h2>

                @if($cultivo->ventas->isEmpty())
                    <p class="text-gray-500 text-center py-4 text-sm">No hay ventas registradas</p>
                @else
                    <div class="space-y-4">
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-700">
                                ${{ number_format($cultivo->ventas->sum('total'), 2) }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1">Total Ventas</div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between py-2 border-b">
                                <span>Número de ventas:</span>
                                <span class="font-semibold">{{ $cultivo->ventas->count() }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span>Promedio por venta:</span>
                                <span class="font-semibold">${{ number_format($cultivo->ventas->avg('total'), 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('ventas.create', ['cultivo_id' => $cultivo->id]) }}" 
                   class="block text-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition text-sm mt-4">
                    <i class="fas fa-plus mr-2"></i> Registrar Venta
                </a>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Acciones</h2>
                <div class="space-y-2">
                    <a href="{{ route('actualizaciones.create', ['cultivo_id' => $cultivo->id]) }}" 
                       class="block w-full text-center bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-edit mr-2"></i> Actualizar Estado
                    </a>
                    <a href="{{ route('cosechas.create', ['cultivo_id' => $cultivo->id]) }}" 
                       class="block w-full text-center bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-hand-holding-seedling mr-2"></i> Registrar Cosecha
                    </a>
                    <a href="{{ route('ventas.create', ['cultivo_id' => $cultivo->id]) }}" 
                       class="block w-full text-center bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-dollar-sign mr-2"></i> Registrar Venta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection