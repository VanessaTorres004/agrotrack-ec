@extends('layouts.app')

@section('title', 'Dashboard Administrador - AgroTrack EC')
@section('page-title', 'Dashboard Administrativo')

@section('content')
<div class="space-y-6">
    
    <!-- Tarjetas Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        
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
                    <p class="text-gray-500 text-sm font-semibold">Cultivos Activos</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $cultivosActivos }}</p>
                </div>
                <div class="w-14 h-14 bg-agro-accent rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="wheat" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Ganado</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $totalGanado ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="cow" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Promedio IDC</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ number_format($promedioIDC, 1) }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Alertas Activas</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $alertasActivas }}</p>
                </div>
                <div class="w-14 h-14 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="alert-triangle" class="w-7 h-7"></i>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Tabs de Gestión -->
    <div class="card">
        <div class="border-b border-gray-200">
            <nav class="flex gap-4 px-6 overflow-x-auto">
                <button onclick="showTab('resumen')" class="tab-button active">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Resumen
                </button>
                <button onclick="showTab('cultivos')" class="tab-button">
                    <i data-lucide="wheat" class="w-4 h-4"></i>
                    Cultivos
                </button>
                <button onclick="showTab('ganado')" class="tab-button">
                    <i data-lucide="cow" class="w-4 h-4"></i>
                    Ganado
                </button>
                <button onclick="showTab('vacunas')" class="tab-button">
                    <i data-lucide="syringe" class="w-4 h-4"></i>
                    Vacunas
                </button>
                <button onclick="showTab('predicciones')" class="tab-button">
                    <i data-lucide="brain" class="w-4 h-4"></i>
                    Predicciones
                </button>
                <button onclick="showTab('clima')" class="tab-button">
                    <i data-lucide="cloud" class="w-4 h-4"></i>
                    Clima
                </button>
                <button onclick="showTab('reportes')" class="tab-button">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Reportes
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Tab Resumen -->
            <div id="tab-resumen" class="tab-content">
                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    
                    <!-- Rendimiento por Cultivo -->
                    <div class="bg-agro-sand rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-agro-dark mb-4">Rendimiento por Cultivo</h3>
                        <canvas id="rendimientoChart"></canvas>
                    </div>

                    <!-- Clasificación IDC -->
                    <div class="bg-agro-sand rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-agro-dark mb-4">Clasificación IDC</h3>
                        <canvas id="clasificacionChart"></canvas>
                    </div>
                    
                </div>

                <!-- Tabla de Productores -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-agro-dark">Resumen de Productores</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reportes.pdf') }}" class="btn-secondary flex items-center gap-2">
                            <i data-lucide="file-pdf" class="w-4 h-4"></i>
                            Exportar PDF
                        </a>
                        <a href="{{ route('admin.reportes.csv') }}" class="btn-primary flex items-center gap-2">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                            Exportar CSV
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
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
                            @foreach($productores as $productor)
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Cultivos -->
            <div id="tab-cultivos" class="tab-content hidden">
                <h3 class="text-xl font-semibold text-agro-dark mb-4">Gestión de Cultivos</h3>
                <p class="text-gray-600">Vista completa disponible en la sección de <a href="{{ route('admin.cultivos') }}" class="text-agro-primary font-semibold hover:text-agro-accent transition-colors">Cultivos</a></p>
            </div>

            <!-- Tab Ganado -->
            <div id="tab-ganado" class="tab-content hidden">
                <h3 class="text-xl font-semibold text-agro-dark mb-4">Gestión de Ganado</h3>
                <p class="text-gray-600 mb-4">Control total del ganado registrado por todos los productores</p>
                <a href="{{ route('ganado.index') }}" class="btn-primary inline-flex items-center gap-2">
                    <i data-lucide="cow" class="w-4 h-4"></i>
                    Ver Todo el Ganado
                </a>
            </div>

            <!-- Tab Vacunas -->
            <div id="tab-vacunas" class="tab-content hidden">
                <h3 class="text-xl font-semibold text-agro-dark mb-4">Control de Vacunas</h3>
                <p class="text-gray-600 mb-4">Historial de vacunación del ganado en todo el sistema</p>
                <p class="text-sm text-gray-500">Las vacunas se gestionan desde cada registro de ganado individual</p>
            </div>

            <!-- Tab Predicciones -->
            <div id="tab-predicciones" class="tab-content hidden">
                <h3 class="text-xl font-semibold text-agro-dark mb-4">Predicciones de Semillas</h3>
                <p class="text-gray-600 mb-4">Sistema de predicción inteligente para optimizar la selección de semillas</p>
                <a href="{{ route('predicciones.index') }}" class="btn-primary inline-flex items-center gap-2">
                    <i data-lucide="brain" class="w-4 h-4"></i>
                    Ver Predicciones
                </a>
            </div>

            <!-- Tab Clima -->
            <div id="tab-clima" class="tab-content hidden">
                <h3 class="text-xl font-semibold text-agro-dark mb-4">Registros Climáticos</h3>
                <p class="text-gray-600">Vista completa en <a href="{{ route('clima.index') }}" class="text-agro-primary font-semibold hover:text-agro-accent transition-colors">Gestión de Clima</a></p>
            </div>

            <!-- Tab Reportes -->
            <div id="tab-reportes" class="tab-content hidden">
                <h3 class="text-xl font-semibold text-agro-dark mb-4">Reportes y Exportación</h3>
                <p class="text-gray-600">Vista completa en <a href="{{ route('admin.reportes') }}" class="text-agro-primary font-semibold hover:text-agro-accent transition-colors">Reportes</a></p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Initialize icons
    lucide.createIcons();
    
    // Función para cambiar tabs
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        event.target.closest('button').classList.add('active');
        lucide.createIcons();
    }

    // Gráfico de Rendimiento
    const ctxRendimiento = document.getElementById('rendimientoChart').getContext('2d');
    new Chart(ctxRendimiento, {
        type: 'bar',
        data: {
            labels: {!! json_encode($rendimientoPorCultivo->pluck('nombre')) !!},
            datasets: [{
                label: 'Rendimiento Promedio',
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
