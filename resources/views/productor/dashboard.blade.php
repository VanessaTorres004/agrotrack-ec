@extends('layouts.app')

@section('title', 'Dashboard - AgroTrack')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Dashboard del Productor</h2>
            <p class="text-gray-600 mt-1">Monitoreo de cultivos e Índice de Desempeño</p>
        </div>
        <a href="{{ route('cultivos.create') }}" 
           class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg flex items-center space-x-2 transition shadow-md">
            <i class="fas fa-plus"></i>
            <span>Nuevo Cultivo</span>
        </a>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-gradient-to-r from-primary-50 to-green-50 rounded-xl p-6 border border-primary-200">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-bolt text-primary-600 mr-2"></i>
            Acciones Rápidas
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Crear Cultivo -->
            <a href="{{ route('cultivos.create') }}" 
               class="bg-white hover:bg-primary-50 rounded-lg p-4 border-2 border-primary-200 hover:border-primary-400 transition group">
                <div class="flex items-center space-x-3">
                    <div class="bg-primary-100 group-hover:bg-primary-200 rounded-lg p-3 transition">
                        <i class="fas fa-seedling text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-primary-700 transition">Nuevo Cultivo</p>
                        <p class="text-xs text-gray-500">Registrar cultivo</p>
                    </div>
                </div>
            </a>
            
            <!-- Registrar Actividad -->
            <a href="{{ route('actualizaciones.create') }}" 
               class="bg-white hover:bg-blue-50 rounded-lg p-4 border-2 border-blue-200 hover:border-blue-400 transition group">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-100 group-hover:bg-blue-200 rounded-lg p-3 transition">
                        <i class="fas fa-tasks text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-blue-700 transition">Registrar Actividad</p>
                        <p class="text-xs text-gray-500">Riego, fertilización, etc.</p>
                    </div>
                </div>
            </a>
            
            <!-- Registrar Cosecha -->
            <a href="{{ route('cosechas.create') }}" 
               class="bg-white hover:bg-yellow-50 rounded-lg p-4 border-2 border-yellow-200 hover:border-yellow-400 transition group">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-100 group-hover:bg-yellow-200 rounded-lg p-3 transition">
                        <i class="fas fa-shopping-basket text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-yellow-700 transition">Registrar Cosecha</p>
                        <p class="text-xs text-gray-500">Registrar producción</p>
                    </div>
                </div>
            </a>
            
            <!-- Registrar Clima -->
            <a href="{{ route('clima.create') }}" 
               class="bg-white hover:bg-cyan-50 rounded-lg p-4 border-2 border-cyan-200 hover:border-cyan-400 transition group">
                <div class="flex items-center space-x-3">
                    <div class="bg-cyan-100 group-hover:bg-cyan-200 rounded-lg p-3 transition">
                        <i class="fas fa-cloud-sun text-cyan-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 group-hover:text-cyan-700 transition">Registrar Clima</p>
                        <p class="text-xs text-gray-500">Condiciones climáticas</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Cultivos -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-primary-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Cultivos</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalCultivos }}</p>
                    <p class="text-sm text-gray-500 mt-1">Activos en todas las fincas</p>
                </div>
                <div class="bg-primary-100 rounded-full p-4">
                    <i class="fas fa-leaf text-primary-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Promedio IDC -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Promedio IDC</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($promedioIDC, 1) }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($promedioIDC >= 90)
                            <span class="text-green-600 font-semibold">Excelente desempeño</span>
                        @elseif($promedioIDC >= 80)
                            <span class="text-blue-600 font-semibold">Buen desempeño</span>
                        @elseif($promedioIDC >= 60)
                            <span class="text-yellow-600 font-semibold">En observación</span>
                        @else
                            <span class="text-red-600 font-semibold">Requiere atención</span>
                        @endif
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Cultivos en Riesgo -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Cultivos en Riesgo</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $cultivosEnRiesgo }}</p>
                    <p class="text-sm text-gray-500 mt-1">IDC menor a 60</p>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts and Alerts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- IDC Chart - Fixed centered layout -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-chart-bar text-primary-600 mr-2"></i>
                Índice de Desempeño del Cultivo (IDC)
            </h3>
            <div class="w-full" style="max-width: 100%; overflow-x: auto;">
                <div style="position: relative; height: 300px; min-width: 300px;">
                    <canvas id="idcChart"></canvas>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3 justify-center">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span class="text-sm text-gray-600">Excelente (90+)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div>
                    <span class="text-sm text-gray-600">Bueno (80-89)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                    <span class="text-sm text-gray-600">En Riesgo (60-79)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-red-500 rounded"></div>
                    <span class="text-sm text-gray-600">Crítico (<60)</span>
                </div>
            </div>
        </div>
        
    <!-- Alertas Section -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-bell text-red-600 mr-2"></i>
            Alertas Recientes
        </h3>
        <div class="space-y-3 max-h-[340px] overflow-y-auto">
            @forelse($alertas as $alerta)
            <div class="border-l-4 {{ $alerta->prioridad === 'alta' ? 'border-red-500 bg-red-50' : ($alerta->prioridad === 'media' ? 'border-yellow-500 bg-yellow-50' : 'border-blue-500 bg-blue-50') }} p-3 rounded-r-lg">
                <div class="flex items-start space-x-2">
                    <i class="fas fa-info-circle {{ $alerta->prioridad === 'alta' ? 'text-red-600' : ($alerta->prioridad === 'media' ? 'text-yellow-600' : 'text-blue-600') }} mt-1"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $alerta->cultivo_nombre }}</p>
                        <p class="text-xs text-gray-700 mt-1">{{ $alerta->mensaje }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $alerta->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-check-circle text-4xl mb-2"></i>
                <p class="text-sm">No hay alertas pendientes</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Cultivos Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-table text-primary-600 mr-2"></i>
                Resumen de Cultivos
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cultivo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Finca</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">IDC</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden md:table-cell">Rendimiento</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden md:table-cell">Calidad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden lg:table-cell">Oportunidad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($cultivos as $cultivo)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-seedling text-primary-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $cultivo->nombre }}</p>
                                    <p class="text-sm text-gray-500">{{ $cultivo->variedad }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $cultivo->finca->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if($cultivo->indicador)
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold {{ $cultivo->indicador->idc >= 90 ? 'text-green-600' : ($cultivo->indicador->idc >= 80 ? 'text-blue-600' : ($cultivo->indicador->idc >= 60 ? 'text-yellow-600' : 'text-red-600')) }}">
                                    {{ number_format($cultivo->indicador->idc, 1) }}
                                </span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $cultivo->indicador->idc >= 90 ? 'bg-green-100 text-green-800' : ($cultivo->indicador->idc >= 80 ? 'bg-blue-100 text-blue-800' : ($cultivo->indicador->idc >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ $cultivo->indicador->clasificacion }}
                                </span>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">Sin calcular</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            @if($cultivo->indicador)
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 w-16">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $cultivo->indicador->rendimiento }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($cultivo->indicador->rendimiento, 0) }}%</span>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            @if($cultivo->indicador)
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 w-16">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $cultivo->indicador->calidad }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($cultivo->indicador->calidad, 0) }}%</span>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            @if($cultivo->indicador)
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 w-16">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $cultivo->indicador->oportunidad }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($cultivo->indicador->oportunidad, 0) }}%</span>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('cultivos.show', $cultivo->id) }}" 
                               class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                Ver Detalle <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-seedling text-gray-300 text-5xl mb-3"></i>
                            <p class="text-gray-500 mb-4">No tienes cultivos registrados</p>
                            <a href="{{ route('cultivos.create') }}" 
                               class="inline-flex items-center space-x-2 bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition">
                                <i class="fas fa-plus"></i>
                                <span>Crear Primer Cultivo</span>
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// IDC Chart with proper centering
const ctx = document.getElementById('idcChart').getContext('2d');
const cultivos = @json($cultivos->map(fn($c) => [
    'nombre' => $c->nombre,
    'idc' => $c->indicador ? $c->indicador->idc : 0,
    'clasificacion' => $c->indicador ? $c->indicador->clasificacion : 'sin_datos'
]));

const idcChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: cultivos.map(c => c.nombre),
        datasets: [{
            label: 'IDC',
            data: cultivos.map(c => c.idc),
            backgroundColor: cultivos.map(c => {
                if (c.idc >= 90) return 'rgba(34, 197, 94, 0.8)';
                if (c.idc >= 80) return 'rgba(59, 130, 246, 0.8)';
                if (c.idc >= 60) return 'rgba(234, 179, 8, 0.8)';
                return 'rgba(239, 68, 68, 0.8)';
            }),
            borderColor: cultivos.map(c => {
                if (c.idc >= 90) return 'rgb(34, 197, 94)';
                if (c.idc >= 80) return 'rgb(59, 130, 246)';
                if (c.idc >= 60) return 'rgb(234, 179, 8)';
                return 'rgb(239, 68, 68)';
            }),
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#4ade80',
                borderWidth: 1,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return 'IDC: ' + context.parsed.y.toFixed(1);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value;
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endpush
