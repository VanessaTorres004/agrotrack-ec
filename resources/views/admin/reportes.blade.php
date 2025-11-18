@extends('layouts.app')

@section('title', 'Reportes - AgroTrack EC')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">📈 Reportes y Análisis</h2>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                🖨️ Imprimir
            </button>
        </div>
    </div>

    <!-- Filtros de Fecha -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">📅 Período del Reporte</h3>
        <form method="GET" action="{{ route('admin.reportes') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agro-green focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agro-green focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-agro-green text-white rounded-lg hover:bg-green-700 transition font-medium">
                    🔍 Filtrar
                </button>
            </div>
        </form>
        
        <div class="mt-4 flex gap-2">
            <a href="{{ route('admin.reportes.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" 
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                📄 Exportar PDF
            </a>
            <a href="{{ route('admin.reportes.csv', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                📊 Exportar CSV
            </a>
        </div>
    </div>

    <!-- Estadísticas Generales Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-agro-green">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Productores</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalProductores }}</p>
                </div>
                <div class="w-12 h-12 bg-agro-green rounded-full flex items-center justify-center text-white text-2xl">
                    👥
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-agro-yellow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Cultivos</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalCultivos }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $cultivosActivos }} activos</p>
                </div>
                <div class="w-12 h-12 bg-agro-yellow rounded-full flex items-center justify-center text-white text-2xl">
                    🌾
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Ganado</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalGanado }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ number_format($totalHectareas, 1) }} hectáreas</p>
                </div>
                <div class="w-12 h-12 bg-amber-600 rounded-full flex items-center justify-center text-white text-2xl">
                    🐄
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Promedio IDC</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($promedioIDC, 1) }}</p>
                    <p class="text-sm {{ $alertasActivas > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">
                        {{ $alertasActivas }} alertas activas
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl">
                    📊
                </div>
            </div>
        </div>
        
    </div>

    <!-- Producción y Ventas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📦 Producción en el Período</h3>
            <div class="text-center py-8">
                <p class="text-5xl font-bold text-agro-green">{{ number_format($totalProduccion, 0) }}</p>
                <p class="text-gray-600 mt-2">kilogramos producidos</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Ventas en el Período</h3>
            <div class="text-center py-8">
                <p class="text-5xl font-bold text-agro-green">${{ number_format($totalVentas, 2) }}</p>
                <p class="text-gray-600 mt-2">en ventas totales</p>
            </div>
        </div>
        
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Rendimiento por Cultivo -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🌾 Rendimiento por Cultivo</h3>
            <canvas id="rendimientoChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>

        <!-- Clasificación IDC -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Clasificación IDC</h3>
            <canvas id="clasificacionChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>
        
    </div>

    <!-- Top 5 Productores -->
    @if($topProductores->count() > 0)
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">🏆 Top 5 Productores por Producción</h3>
        <div class="space-y-3">
            @foreach($topProductores as $index => $productor)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-agro-green text-white flex items-center justify-center font-bold">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $productor['nombre'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-agro-green">{{ number_format($productor['produccion'], 0) }}</p>
                    <p class="text-sm text-gray-500">kg producidos</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Distribución de Cultivos -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">🌱 Distribución de Cultivos</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($distribucionCultivos as $cultivo)
            <div class="bg-agro-bg p-4 rounded-lg text-center">
                <p class="text-3xl font-bold text-agro-green">{{ $cultivo->total }}</p>
                <p class="text-gray-700 font-medium mt-2">{{ $cultivo->nombre }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tabla de Productores -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">👥 Resumen Detallado de Productores</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-agro-bg text-left">
                        <th class="px-4 py-3 font-semibold text-gray-700">Nombre</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Finca</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Cultivos</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Promedio IDC</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productores as $productor)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $productor['nombre'] }}</td>
                        <td class="px-4 py-3">{{ $productor['finca'] }}</td>
                        <td class="px-4 py-3">{{ $productor['cultivos'] }}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold">{{ number_format($productor['promedio_idc'], 1) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $productor['estado'] === 'Bueno' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $productor['estado'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            No hay productores registrados
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
    // Gráfico de Rendimiento
    const ctxRendimiento = document.getElementById('rendimientoChart').getContext('2d');
    new Chart(ctxRendimiento, {
        type: 'bar',
        data: {
            labels: {!! json_encode($rendimientoPorCultivo->pluck('nombre')) !!},
            datasets: [{
                label: 'Rendimiento Promedio (IDC)',
                data: {!! json_encode($rendimientoPorCultivo->pluck('promedio')) !!},
                backgroundColor: '#2E7D32',
                borderColor: '#1B5E20',
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
                backgroundColor: ['#2E7D32', '#81C784', '#FBC02D', '#E57373'],
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