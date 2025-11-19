@extends('layouts.app')

@section('title', 'Módulo de Ganado - AgroTrack EC')
@section('page-title', 'Módulo de Ganado')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-semibold text-agro-dark">Módulo de Ganado</h2>
            <p class="text-gray-600 mt-1">Control Sanitario y Productivo</p>
        </div>
        <a href="{{ route('ganado.create') }}" class="btn-primary flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Registrar Nuevo Animal
        </a>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card stat-card-primary">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Total de Animales</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $estadisticas['total'] }}</p>
                </div>
                <div class="w-14 h-14 bg-agro-primary rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="cow" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">% Vacunados</p>
                    <p class="text-3xl font-bold text-agro-primary mt-2">{{ number_format(($estadisticas['total'] > 0 ? ($estadisticas['vacunados'] / $estadisticas['total']) * 100 : 0), 1) }}%</p>
                </div>
                <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="syringe" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Control Pendiente</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $estadisticas['pendientes'] }}</p>
                </div>
                <div class="w-14 h-14 bg-yellow-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="clock" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-agro-accent">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">ISA</p>
                    <p class="text-3xl font-bold text-agro-primary mt-2">{{ $estadisticas['isa'] }}</p>
                </div>
                <div class="w-14 h-14 bg-agro-accent rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="trending-up" class="w-7 h-7"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="card overflow-hidden p-0">
        <div class="px-6 py-4 border-b border-gray-200 bg-agro-sand">
            <h3 class="text-xl font-semibold text-agro-dark">Listado de Ganado</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">Raza</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">Edad (meses)</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">Peso (kg)</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">Vacunas</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-agro-dark uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ganado as $animal)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-agro-dark">{{ $animal->identificador }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 capitalize">{{ $animal->tipo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $animal->raza }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $animal->edad_meses }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($animal->peso_kg, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($animal->estado_salud === 'sano')
                                <span class="badge badge-success">Sano</span>
                            @elseif($animal->estado_salud === 'observacion')
                                <span class="badge badge-warning">Observación</span>
                            @else
                                <span class="badge badge-danger">Enfermo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($animal->tieneVacunaVencida())
                                <span class="badge badge-danger">Vacuna vencida</span>
                            @elseif($animal->tieneVacunaPendiente())
                                <span class="badge badge-warning">Próxima en 7 días</span>
                            @elseif($animal->estaVacunado())
                                <span class="badge badge-success">Al día</span>
                            @else
                                <span class="badge">Sin vacunas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('ganado.edit', $animal) }}" class="text-agro-primary hover:text-agro-accent transition-colors">Editar</a>
                            <a href="{{ route('vacunas.create', $animal) }}" class="text-green-600 hover:text-green-800 transition-colors flex items-center gap-1">
                                <i data-lucide="syringe" class="w-3 h-3"></i>
                                Añadir Vacuna
                            </a>
                            <a href="{{ route('ganado.vacunas', $animal) }}" class="text-blue-600 hover:text-blue-800 transition-colors">Historial</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <i data-lucide="inbox" class="w-12 h-12 text-gray-400"></i>
                                <p>No hay animales registrados</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold mb-4 text-agro-dark">Estado de Vacunación</h3>
            <canvas id="vacunacionChart"></canvas>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold mb-4 text-agro-dark">Peso Promedio por Tipo</h3>
            <canvas id="pesoChart"></canvas>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold mb-4 text-agro-dark">Estado de Salud</h3>
            <canvas id="saludChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    lucide.createIcons();
    
    // Gráfico de Vacunación
    new Chart(document.getElementById('vacunacionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Vacunados', 'Pendientes'],
            datasets: [{
                data: [{{ $estadisticas['vacunados'] }}, {{ $estadisticas['total'] - $estadisticas['vacunados'] }}],
                backgroundColor: ['#3C8D40', '#FBC02D']
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
                backgroundColor: '#79C86E'
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
                backgroundColor: ['#3C8D40', '#FBC02D', '#E57373']
            }]
        }
    });
</script>
@endpush
@endsection
