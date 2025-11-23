@extends('layouts.app')

@section('title', 'Reportes - AgroTrack EC')
@section('page-title', 'Reportes y Análisis')
@section('page-subtitle', 'Resumen general del rendimiento agrícola')

@section('content')
<div class="space-y-8">

    <!-- ==== FILTROS ==== -->
    <div class="card p-6">
        <h3 class="text-xl font-semibold text-agro-dark mb-4">Filtrar por período</h3>

        <form method="GET" action="{{ route('admin.reportes') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Fecha Inicio -->
            <div>
                <label class="block font-semibold text-agro-dark mb-1 text-sm">Fecha inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="input-modern">
            </div>

            <!-- Fecha Fin -->
            <div>
                <label class="block font-semibold text-agro-dark mb-1 text-sm">Fecha fin</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="input-modern">
            </div>

            <!-- Botón -->
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Filtrar
                </button>
            </div>
        </form>

        <!-- Exportación -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.reportes.pdf', ['fecha_inicio'=>$fechaInicio,'fecha_fin'=>$fechaFin]) }}"
               class="btn-secondary flex items-center gap-2">
                <i data-lucide="file-pdf" class="w-4 h-4"></i> PDF
            </a>

            <a href="{{ route('admin.reportes.csv', ['fecha_inicio'=>$fechaInicio,'fecha_fin'=>$fechaFin]) }}"
               class="btn-primary flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> CSV
            </a>
        </div>
    </div>


    <!-- Estadísticas Generales Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Productores -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Productores</p>
                <p class="text-3xl font-bold text-agro-dark mt-1">{{ $totalProductores }}</p>
            </div>
            <div class="w-14 h-14 bg-agro-primary text-white rounded-xl flex items-center justify-center shadow">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
        </div>

        <!-- Cultivos -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Cultivos</p>
                <p class="text-3xl font-bold text-agro-dark mt-1">{{ $totalCultivos }}</p>
                <p class="text-xs text-agro-primary font-medium mt-1">{{ $cultivosActivos }} activos</p>
            </div>
            <div class="w-14 h-14 bg-agro-accent text-white rounded-xl flex items-center justify-center shadow">
                <i data-lucide="wheat" class="w-7 h-7"></i>
            </div>
        </div>
        <!-- Total Ganado -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Ganado</p>
                <p class="text-3xl font-bold text-agro-dark mt-1">{{ $totalGanado }}</p>
                <p class="text-xs text-gray-500 font-medium mt-1">
                    {{ number_format($totalHectareas, 1) }} ha
                </p>
            </div>

            <!-- Ícono SVG Premium -->
            <div class="w-14 h-14 bg-amber-600 text-white rounded-xl flex items-center justify-center shadow">
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    class="w-7 h-7"
                >
                    <path d="M8 3H5L3 7v4a9 9 0 009 9v0a9 9 0 009-9V7l-2-4h-3" />
                    <path d="M7 10h.01" />
                    <path d="M17 10h.01" />
                </svg>
            </div>
        </div>



        <!-- IDC -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Promedio IDC</p>
                <p class="text-3xl font-bold text-agro-dark mt-1">{{ number_format($promedioIDC, 1) }}</p>
                <p class="text-xs {{ $alertasActivas > 0 ? 'text-red-600' : 'text-agro-primary' }} font-medium mt-1">
                    {{ $alertasActivas }} alertas activas
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center shadow">
                <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
            </div>
        </div>

    </div>



    <!-- ==== PRODUCCIÓN Y VENTAS ==== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark">Producción total</h3>
            <div class="py-6 text-center">
                <p class="text-5xl font-bold text-agro-primary">{{ number_format($totalProduccion, 0) }}</p>
                <p class="text-gray-500">kg producidos</p>
            </div>
        </div>

        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark">Ventas totales</h3>
            <div class="py-6 text-center">
                <p class="text-5xl font-bold text-agro-primary">
                    ${{ number_format($totalVentas,2) }}
                </p>
                <p class="text-gray-500">en ventas</p>
            </div>
        </div>
    </div>


    <!-- ==== GRÁFICOS ==== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark mb-4">Rendimiento por Cultivo</h3>
            <canvas id="rendimientoChart"></canvas>
        </div>

        <div class="card">
            <h3 class="text-xl font-semibold text-agro-dark mb-4">Clasificación IDC</h3>
            <canvas id="clasificacionChart"></canvas>
        </div>
    </div>


    <!-- ==== DISTRIBUCIÓN DE CULTIVOS ==== -->
    <div class="card">
        <h3 class="text-xl font-semibold text-agro-dark mb-4">Distribución de Cultivos</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($distribucionCultivos as $cultivo)
            <div class="bg-agro-sand rounded-lg p-4 text-center border border-gray-200">
                <p class="text-3xl font-bold text-agro-primary">{{ $cultivo->total }}</p>
                <p class="text-gray-700 font-medium mt-2">{{ $cultivo->nombre }}</p>
            </div>
            @endforeach
        </div>
    </div>


    <!-- ==== TABLA DETALLADA ==== -->
    <div class="card">
        <h3 class="text-xl font-semibold text-agro-dark mb-4">Resumen Detallado de Productores</h3>

        <div class="overflow-x-auto">
            <table class="table-modern min-w-full">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Finca</th>
                        <th>Cultivos</th>
                        <th>Promedio IDC</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productores as $p)
                    <tr>
                        <td>{{ $p['nombre'] }}</td>
                        <td class="text-gray-600">{{ $p['finca'] }}</td>
                        <td class="text-gray-700">{{ $p['cultivos'] }}</td>
                        <td><span class="font-semibold text-agro-primary">{{ number_format($p['promedio_idc'], 1) }}</span></td>
                        <td>
                            <span class="badge {{ $p['estado']=='Bueno'?'badge-success':'badge-warning' }}">
                                {{ $p['estado'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 text-gray-400 mx-auto"></i>
                            <p>No hay registros</p>
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

// BAR CHART
new Chart(document.getElementById('rendimientoChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($rendimientoPorCultivo->pluck('nombre')) !!},
        datasets: [{
            data: {!! json_encode($rendimientoPorCultivo->pluck('promedio')) !!},
            backgroundColor: '#3C8D40',
            borderWidth: 0
        }]
    },
    options: { plugins: { legend: { display: false } } }
});

// DONUT
const c = {!! json_encode($clasificacionIDC) !!};
new Chart(document.getElementById('clasificacionChart'), {
    type: 'doughnut',
    data: {
        labels: c.map(i=>i.clasificacion),
        datasets: [{
            data: c.map(i=>i.total),
            backgroundColor: ['#3C8D40','#79C86E','#FBC02D','#E57373'],
            borderWidth: 1,
            borderColor: '#fff'
        }]
    },
    options: { plugins:{ legend:{ position:'bottom' }} }
});
</script>
@endpush
@endsection
