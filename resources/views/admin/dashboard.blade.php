@extends('layouts.app')

@section('title', 'Dashboard Administrador - AgroTrack EC')

@section('content')
<div class="space-y-6">
    
    <h2 class="text-3xl font-bold text-gray-800">Dashboard Administrativo</h2>
    
    <!-- Tarjetas Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        
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
                    <p class="text-gray-500 text-sm font-medium">Cultivos Activos</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $cultivosActivos }}</p>
                </div>
                <div class="w-12 h-12 bg-agro-yellow rounded-full flex items-center justify-center text-white text-2xl">
                    🌾
                </div>
            </div>
        </div>

        <!-- Added Ganado stat card -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Ganado</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalGanado ?? 0 }}</p>
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
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl">
                    📊
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Alertas Activas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $alertasActivas }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center text-white text-2xl">
                    ⚠️
                </div>
            </div>
        </div>
        
    </div>

    <!-- Tabs de Gestión -->
    <div class="bg-white rounded-xl shadow-md">
        <div class="border-b border-gray-200">
            <nav class="flex gap-4 px-6 overflow-x-auto">
                <!-- Added ganado, vacunas, and predicciones tabs -->
                <button onclick="showTab('resumen')" class="tab-button px-4 py-4 font-medium text-agro-green border-b-2 border-agro-green whitespace-nowrap">
                    📊 Resumen
                </button>
                <button onclick="showTab('cultivos')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    🌾 Cultivos
                </button>
                <button onclick="showTab('ganado')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    🐄 Ganado
                </button>
                <button onclick="showTab('vacunas')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    💉 Vacunas
                </button>
                <button onclick="showTab('predicciones')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    🔮 Predicciones
                </button>
                <button onclick="showTab('clima')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    ☁️ Clima
                </button>
                <button onclick="showTab('reportes')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    📈 Reportes
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Tab Resumen -->
            <div id="tab-resumen" class="tab-content">
                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    
                    <!-- Rendimiento por Cultivo -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Rendimiento por Cultivo</h3>
                        <canvas id="rendimientoChart"></canvas>
                    </div>

                    <!-- Clasificación IDC -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Clasificación IDC</h3>
                        <canvas id="clasificacionChart"></canvas>
                    </div>
                    
                </div>

                <!-- Tabla de Productores -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Resumen de Productores</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reportes.pdf') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                            Exportar PDF
                        </a>
                        <a href="{{ route('admin.reportes.csv') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                            Exportar CSV
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
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
                            @foreach($productores as $productor)
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Added tabs for Cultivos, Ganado, Vacunas, Predicciones -->
            <div id="tab-cultivos" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Gestión de Cultivos</h3>
                <p class="text-gray-600">Vista completa disponible en la sección de <a href="{{ route('admin.cultivos') }}" class="text-agro-green font-semibold">Cultivos</a></p>
            </div>

            <div id="tab-ganado" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Gestión de Ganado</h3>
                <p class="text-gray-600 mb-4">Control total del ganado registrado por todos los productores</p>
                <a href="{{ route('ganado.index') }}" class="inline-block px-6 py-3 bg-agro-green text-white rounded-lg hover:bg-green-700 transition font-medium">
                    Ver Todo el Ganado
                </a>
            </div>

            <div id="tab-vacunas" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Control de Vacunas</h3>
                <p class="text-gray-600 mb-4">Historial de vacunación del ganado en todo el sistema</p>
                <p class="text-sm text-gray-500">Las vacunas se gestionan desde cada registro de ganado individual</p>
            </div>

            <div id="tab-predicciones" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Predicciones de Semillas</h3>
                <p class="text-gray-600 mb-4">Sistema de predicción inteligente para optimizar la selección de semillas</p>
                <a href="{{ route('predicciones.index') }}" class="inline-block px-6 py-3 bg-agro-green text-white rounded-lg hover:bg-green-700 transition font-medium">
                    Ver Predicciones
                </a>
            </div>

            <div id="tab-clima" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Registros Climáticos</h3>
                <p class="text-gray-600">Vista completa en <a href="{{ route('clima.index') }}" class="text-agro-green font-semibold">Gestión de Clima</a></p>
            </div>

            <div id="tab-reportes" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Reportes y Exportación</h3>
                <p class="text-gray-600">Vista completa en <a href="{{ route('admin.reportes') }}" class="text-agro-green font-semibold">Reportes</a></p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Función para cambiar tabs
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('text-agro-green', 'border-b-2', 'border-agro-green');
            btn.classList.add('text-gray-500');
        });
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        event.target.classList.remove('text-gray-500');
        event.target.classList.add('text-agro-green', 'border-b-2', 'border-agro-green');
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
