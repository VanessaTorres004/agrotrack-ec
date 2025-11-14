@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🐄 Módulo de Ganado</h1>
            <p class="mt-2 text-gray-600">Control Sanitario y Productivo</p>
        </div>

        <!-- Tarjetas de Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total de Animales</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $estadisticas['total'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">% Vacunados</p>
                        <p class="text-3xl font-bold text-green-600">{{ number_format(($estadisticas['total'] > 0 ? ($estadisticas['vacunados'] / $estadisticas['total']) * 100 : 0), 1) }}%</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Control Pendiente</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $estadisticas['pendientes'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">ISA</p>
                        <p class="text-3xl font-bold text-green-600">{{ $estadisticas['isa'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Añadir -->
        <div class="mb-6">
            <a href="{{ route('ganado.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Registrar Nuevo Animal
            </a>
        </div>

        <!-- Tabla Detallada -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Listado de Ganado</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raza</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edad (meses)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacunas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($ganado as $animal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $animal->identificador }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">{{ $animal->tipo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $animal->raza }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $animal->edad_meses }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($animal->peso_kg, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($animal->estado_salud === 'sano')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">🟢 Sano</span>
                                @elseif($animal->estado_salud === 'observacion')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">🟡 Observación</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🔴 Enfermo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($animal->tieneVacunaVencida())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Vacuna vencida</span>
                                @elseif($animal->tieneVacunaPendiente())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Próxima en 7 días</span>
                                @elseif($animal->estaVacunado())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Al día</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Sin vacunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('ganado.edit', $animal) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                <a href="{{ route('vacunas.create', $animal) }}" class="text-green-600 hover:text-green-900">💉 Añadir Vacuna</a>
                                <a href="{{ route('ganado.vacunas', $animal) }}" class="text-blue-600 hover:text-blue-900">Historial</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No hay animales registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Estado de Vacunación</h3>
                <canvas id="vacunacionChart"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Peso Promedio por Tipo</h3>
                <canvas id="pesoChart"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Estado de Salud</h3>
                <canvas id="saludChart"></canvas>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Vacunación
    new Chart(document.getElementById('vacunacionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Vacunados', 'Pendientes'],
            datasets: [{
                data: [{{ $estadisticas['vacunados'] }}, {{ $estadisticas['total'] - $estadisticas['vacunados'] }}],
                backgroundColor: ['#2E7D32', '#FBC02D']
            }]
        }
    });

    // Gráfico de Peso
    new Chart(document.getElementById('pesoChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($ganado->groupBy('tipo')->keys()) !!},
            datasets: [{
                label: 'Peso Promedio (kg)',
                data: {!! json_encode($ganado->groupBy('tipo')->map(fn($g) => $g->avg('peso_kg'))->values()) !!},
                backgroundColor: '#81C784'
            }]
        }
    });

    // Gráfico de Salud
    new Chart(document.getElementById('saludChart'), {
        type: 'pie',
        data: {
            labels: ['Sanos', 'En Observación', 'Enfermos'],
            datasets: [{
                data: [{{ $estadisticas['sanos'] }}, {{ $estadisticas['observacion'] }}, {{ $estadisticas['enfermos'] }}],
                backgroundColor: ['#2E7D32', '#FBC02D', '#E57373']
            }]
        }
    });
</script>
@endpush
@endsection
