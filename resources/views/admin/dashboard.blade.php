    @extends('layouts.app')

    @section('title', 'Dashboard Administrador - AgroTrack EC')
    @section('page-title', 'Dashboard Administrativo')

    @section('content')
    <div class="min-h-screen bg-agro-sand py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Administrativo</h1>
                <p class="mt-2 text-gray-600">Resumen general del sistema</p>
            </div>

            {{-- TARJETAS SUPERIORES --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                
                {{-- Productores --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Productores</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProductores }}</p>
                        </div>
                        <div class="bg-agro-primary/15 p-3 rounded-full text-agro-primary">
                            <i data-lucide="users" class="w-7 h-7"></i>
                        </div>
                    </div>
                </div>

                {{-- Cultivos activos --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Cultivos Activos</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $cultivosActivos }}</p>
                        </div>
                        <div class="bg-agro-accent/20 p-3 rounded-full text-agro-accent">
                            <i data-lucide="wheat" class="w-7 h-7"></i>
                        </div>
                    </div>
                </div>
                {{-- Ganado --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Ganado</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalGanado ?? 0 }}</p>
                        </div>

                        <!-- Ícono SVG Premium -->
                        <div class="bg-yellow-100 p-3 rounded-full text-yellow-600 flex items-center justify-center">
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
                </div>


                {{-- Promedio IDC --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Promedio IDC</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($promedioIDC, 1) }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                            <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                        </div>
                    </div>
                </div>

                {{-- Alertas --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Alertas Activas</p>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ $alertasActivas }}</p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full text-red-600">
                            <i data-lucide="alert-triangle" class="w-7 h-7"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRÁFICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Rendimiento por Cultivo</h3>
                    <canvas id="rendimientoChart"></canvas>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Clasificación IDC</h3>
                    <canvas id="clasificacionChart"></canvas>
                </div>

            </div>

            {{-- TABLA DE PRODUCTORES --}}
            <div class="mt-10 bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900">Resumen de Productores</h2>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.reportes.pdf') }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold flex items-center gap-2">
                                <i data-lucide="file-pdf" class="w-4 h-4"></i> PDF
                            </a>
                            <a href="{{ route('admin.reportes.csv') }}"
                            class="px-4 py-2 bg-agro-primary hover:bg-agro-accent text-white rounded-lg text-sm font-semibold flex items-center gap-2">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Finca</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Cultivos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">IDC</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($productores as $productor)
                            <tr>
                                <td class="px-6 py-4">{{ $productor['nombre'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $productor['finca'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $productor['cultivos'] }}</td>
                                <td class="px-6 py-4 font-semibold text-agro-primary">
                                    {{ number_format($productor['promedio_idc'], 1) }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $st = $productor['estado'];
                                        $badge = match ($st) {
                                            'Bueno' => 'bg-green-100 text-green-700',
                                            'En Riesgo' => 'bg-yellow-100 text-yellow-700',
                                            'Crítico' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ $st }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        lucide.createIcons();

        // === GRÁFICO RENDIMIENTO ===
        const ctxR = document.getElementById('rendimientoChart').getContext('2d');
        new Chart(ctxR, {
            type: 'bar',
            data: {
                labels: {!! json_encode($rendimientoPorCultivo->pluck('nombre')) !!},
                datasets: [{
                    label: 'Rendimiento Promedio',
                    data: {!! json_encode($rendimientoPorCultivo->pluck('promedio')) !!},
                    backgroundColor: '#3C8D40'
                }]
            }
        });

        // === CLASIFICACIÓN IDC ===
        const clasificacionData = {!! json_encode($clasificacionIDC) !!};

        new Chart(document.getElementById('clasificacionChart'), {
            type: 'doughnut',
            data: {
                labels: clasificacionData.map(x => x.clasificacion),
                datasets: [{
                    data: clasificacionData.map(x => x.total),
                    backgroundColor: ['#3C8D40', '#79C86E', '#FBC02D', '#E57373']
                }]
            }
        });
    </script>
    @endpush

    @endsection
