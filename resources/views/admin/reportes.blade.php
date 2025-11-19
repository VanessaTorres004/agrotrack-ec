@extends('layouts.app')

@section('title', 'Reportes - AgroTrack EC')
@section('page-title', 'Reportes y Análisis')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-semibold text-agro-dark">Reportes y Análisis</h2>
            <p class="text-gray-600 mt-1">Análisis completo del sistema</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn-secondary flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Filtros de Fecha -->
    <div class="card">
        <h3 class="text-lg font-semibold text-agro-dark mb-4">Período del Reporte</h3>
        <form method="GET" action="{{ route('admin.reportes') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-agro-dark mb-2">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="input-modern">
            </div>
            <div>
                <label class="block text-sm font-semibold text-agro-dark mb-2">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="input-modern">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Filtrar
                </button>
            </div>
        </form>
        
        <div class="mt-4 flex gap-2">
            <a href="{{ route('admin.reportes.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" 
               class="btn-secondary flex items-center gap-2">
                <i data-lucide="file-pdf" class="w-4 h-4"></i>
                Exportar PDF
            </a>
            <a href="{{ route('admin.reportes.csv', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" 
               class="btn-primary flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                Exportar CSV
            </a>
        </div>
    </div>

    <!-- Estadísticas Generales Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="stat-card stat-card-primary">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Productores</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $totalProductores }}</p>
                </div>
                <div class="w-14 h-14 bg-agro-primary rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-agro-accent">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Cultivos</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $totalCultivos }}</p>
                    <p class="text-sm text-agro-primary mt-1 font-medium">{{ $cultivosActivos }} activos</p>
                </div>
                <div class="w-14 h-14 bg-agro-accent rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="wheat" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-amber-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Ganado</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $totalGanado }}</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">{{ number_format($totalHectareas, 1) }} hectáreas</p>
                </div>
                <div class="w-14 h-14 bg-amber-600 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="cow" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Promedio IDC</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ number_format($promedioIDC, 1) }}</p>
                    <p class="text-sm {{ $alertasActivas > 0 ? 'text-red-600' : 'text-agro-primary' }} mt-1 font-medium">
                        {{ $alertasActivas }} alertas activas
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Producción y Ventas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark mb-4">Producción en el Período</h3>
            <div class="text-center py-8">
                <p class="text-5xl font-bold text-agro-primary">{{ number_format($totalProduccion, 0) }}</p>
                <p class="text-gray-600 mt-2">kilogramos producidos</p>
            </div>
        </div>

        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark mb-4">Ventas en el Período</h3>
            <div class="text-center py-8">
                <p class="text-5xl font-bold text-agro-primary">${{ number_format($totalVentas, 2) }}</p>
                <p class="text-gray-600 mt-2">en ventas totales</p>
            </div>
        </div>
        
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Rendimiento por Cultivo -->
        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark mb-4">Rendimiento por Cultivo</h3>
            <canvas id="rendimientoChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>

        <!-- Clasificación IDC -->
        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark mb-4">Clasificación IDC</h3>
            <canvas id="clasificacionChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>
        
    </div>

    <!-- Top 5 Productores -->
    @if($topProductores->count() > 0)
    <div class="card">
        <h3 class="text-xl font-semibold text-agro-dark mb-4">Top 5 Productores por Producción</h3>
        <div class="space-y-3">
            @foreach($topProductores as $index => $productor)
            <div class="flex items-center justify-between p-4 bg-agro-sand rounded-lg">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-agro-primary text-white flex items-center justify-center font-bold">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <p class="font-semibold text-agro-dark">{{ $productor['nombre'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-agro-primary">{{ number_format($productor['produccion'], 0) }}</p>
                    <p class="text-sm text-gray-500">kg producidos</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Distribución de Cultivos -->
    <div class="card">
        <h3 class="text-xl font-semibold text-agro-dark mb-4">Distribución de Cultivos</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($distribucionCultivos as $cultivo)
            <div class="bg-agro-sand p-4 rounded-lg text-center">
                <p class="text-3xl font-bold text-agro-primary">{{ $cultivo->total }}</p>
                <p class="text-gray-700 font-medium mt-2">{{ $cultivo->nombre }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tabla de Productores -->
    <div class="card">
        <h3 class="text-xl font-semibold text-agro-dark mb-4">Resumen Detallado de Productores</h3>
        
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="px-4 py-3 font-semibold text-agro-dark text-left">Nombre</th>
                        <th class="px-4 py-3 font-semibold text-agro-dark text-left">Finca</th>
                        <th class="px-4 py-3 font-semibold text-agro-dark text-left">Cultivos</th>
                        <th class="px-4 py-3 font-semibold text-agro-dark text-left">Promedio IDC</th>
                        <th class="px-4 py-3 font-semibold text-agro-dark text-left">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productores as $productor)
                    <tr>
                        <td class="px-4 py-3 text-agro-dark">{{ $productor['nombre'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $productor['finca'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $productor['cultivos'] }}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-agro-primary">{{ number_format($productor['promedio_idc'], 1) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $productor['estado'] === 'Bueno' ? 'badge-success' : 'badge-warning' }}">
                                {{ $productor['estado'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <i data-lucide="inbox" class="w-12 h-12 text-gray-400"></i>
                                <p>No hay productores registrados</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    lucide.createIcons();
    
    // Gráfico de Rendimiento
    const ctxRendimiento = document.getElementById('rendimientoChart').getContext('2d');
    new Chart(ctxRendimiento, {
        type: 'bar',
        data: {
            labels: {!! json_encode($rendimientoPorCultivo->pluck('nombre')) !!},
            datasets: [{
                label: 'Rendimiento Promedio (IDC)',
                data: {!! json_encode($rendimientoPorCultivo->pluck('promedio')) !!},
                backgroundColor: '#3C8D40',
                borderColor: '#2F2F2F',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Gráfico de Clasificación
    const ctxClasificacion = document.getElementById('clasificacionChart').getContext('2d');
    const clasificacionData = {!! json_encode($clasificacionIDC) !!};
    const labels = clasificacionData.map(c => {
        const map = {'excelente': 'Excelente', 'bueno': 'Bueno', 'en_riesgo': 'En Riesgo', 'critico': 'Crítico'};
        return map[c.clasificacion] || c.clasificacion;
    });
    const valores = clasificacionData.map(c => c.total);
    
    new Chart(ctxClasificacion, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: ['#3C8D40', '#79C86E', '#FBC02D', '#E57373'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection
