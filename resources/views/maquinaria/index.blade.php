@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🚜 Módulo de Maquinaria</h1>
            <p class="mt-2 text-gray-600">Gestión y Mantenimiento</p>
        </div>

        <!-- Tarjetas Superiores -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total de Máquinas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $estadisticas['total'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Operativas</p>
                        <p class="text-3xl font-bold text-green-600">{{ $estadisticas['operativas'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">En Mantenimiento</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $estadisticas['mantenimiento'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Eficiencia</p>
                        <p class="text-3xl font-bold text-green-600">{{ $estadisticas['eficiencia'] }}%</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="mb-6 flex gap-4">
            <a href="{{ route('maquinaria.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Añadir Máquina
            </a>
        </div>

        <!-- Tabla de Maquinaria -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Listado de Maquinaria</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca/Modelo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas de Uso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Último Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alertas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($maquinaria as $maquina)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $maquina->identificador }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">{{ $maquina->tipo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $maquina->marca }} {{ $maquina->modelo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($maquina->horas_uso) }} hrs</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($maquina->estado === 'operativa')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Operativa</span>
                                @elseif($maquina->estado === 'mantenimiento')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Mantenimiento</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Fuera de Servicio</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $maquina->fecha_ultimo_servicio ? $maquina->fecha_ultimo_servicio->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($maquina->mantenimientoVencido())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Mantenimiento vencido</span>
                                @elseif($maquina->necesitaMantenimiento())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Próximo en 3 días</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">OK</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('maquinaria.edit', $maquina) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                <a href="{{ route('mantenimientos.create', $maquina) }}" class="text-green-600 hover:text-green-900">🛠️ Registrar Mantenimiento</a>
                                <a href="{{ route('maquinaria.mantenimientos', $maquina) }}" class="text-blue-600 hover:text-blue-900">Historial</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No hay maquinaria registrada</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Distribución por Tipo</h3>
                <canvas id="tipoChart"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Costos de Mantenimiento (Último Año)</h3>
                <canvas id="costosChart"></canvas>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Tipo
    new Chart(document.getElementById('tipoChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($maquinaria->groupBy('tipo')->keys()) !!},
            datasets: [{
                data: {!! json_encode($maquinaria->groupBy('tipo')->map(fn($g) => $g->count())->values()) !!},
                backgroundColor: ['#2E7D32', '#81C784', '#FBC02D', '#FFD600', '#E8F5E9']
            }]
        }
    });

    // Gráfico de Costos
    new Chart(document.getElementById('costosChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($maquinaria->pluck('identificador')) !!},
            datasets: [{
                label: 'Costo Total ($)',
                data: {!! json_encode($maquinaria->map(fn($m) => $m->costoMantenimientoTotal())) !!},
                backgroundColor: '#FBC02D'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection
