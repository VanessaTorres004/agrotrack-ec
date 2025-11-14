@extends('layouts.app')

@section('title', 'Mi Panel - AgroTrack EC')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Mi Panel de Gestión</h2>
        <a href="{{ route('cultivos.create') }}" class="px-6 py-3 bg-agro-green text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2">
            <span>➕</span>
            Agregar Cultivo
        </a>
    </div>

    <!-- Alertas -->
    @if($alertas->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
        <h3 class="font-bold text-yellow-800 mb-2">⚠️ Alertas Activas ({{ $alertas->count() }})</h3>
        <ul class="space-y-2">
            @foreach($alertas->take(3) as $alerta)
            <li class="text-sm text-yellow-700">
                <span class="font-semibold">{{ $alerta->titulo }}:</span> {{ $alerta->mensaje }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tabs de Secciones -->
    <div class="bg-white rounded-xl shadow-md">
        <div class="border-b border-gray-200">
            <nav class="flex gap-4 px-6 overflow-x-auto">
                <!-- Added ganado and predicciones tabs -->
                <button onclick="showTab('cultivos')" class="tab-button px-4 py-4 font-medium text-agro-green border-b-2 border-agro-green whitespace-nowrap">
                    🌾 Cultivos
                </button>
                <button onclick="showTab('ganado')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    🐄 Ganado
                </button>
                <button onclick="showTab('actualizaciones')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    📝 Actualizaciones
                </button>
                <button onclick="showTab('clima')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    ☁️ Clima
                </button>
                <button onclick="showTab('cosechas')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    🚜 Cosechas
                </button>
                <button onclick="showTab('ventas')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    💰 Ventas
                </button>
                <button onclick="showTab('predicciones')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    🔮 Predicciones
                </button>
                <button onclick="showTab('indicadores')" class="tab-button px-4 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    📊 Indicadores
                </button>
            </nav>
        </div>

        <!-- Contenido de Tabs -->
        <div class="p-6">
            
            <!-- Tab Cultivos -->
            <div id="tab-cultivos" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($fincas as $finca)
                        @foreach($finca->cultivos as $cultivo)
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="text-lg font-bold text-gray-800">{{ $cultivo->nombre }}</h4>
                                @php
                                    $idc = $cultivo->idc_actual;
                                    $colorClass = $idc >= 90 ? 'bg-green-600' : ($idc >= 80 ? 'bg-green-400' : ($idc >= 60 ? 'bg-yellow-400' : 'bg-red-400'));
                                @endphp
                                <span class="px-3 py-1 {{ $colorClass }} text-white rounded-full text-sm font-bold">
                                    {{ number_format($idc, 1) }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Variedad:</span> {{ $cultivo->variedad ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Área:</span> {{ $cultivo->area }} ha</p>
                            <p class="text-sm text-gray-600 mb-3"><span class="font-medium">Estado:</span> 
                                <span class="capitalize {{ $cultivo->estado === 'activo' ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $cultivo->estado }}
                                </span>
                            </p>
                            
                            <a href="{{ route('cultivos.show', $cultivo) }}" class="block text-center px-4 py-2 bg-agro-green text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                Ver Detalles
                            </a>
                        </div>
                        @endforeach
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <p class="text-gray-500 mb-4">No tienes cultivos registrados</p>
                            <a href="{{ route('cultivos.create') }}" class="inline-block px-6 py-3 bg-agro-green text-white rounded-lg hover:bg-green-700 transition font-medium">
                                Crear primer cultivo
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Added Ganado tab content -->
            <div id="tab-ganado" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Mi Ganado</h3>
                    <a href="{{ route('ganado.create') }}" class="px-4 py-2 bg-agro-green text-white rounded-lg hover:bg-green-700 transition font-medium">
                        Agregar Ganado
                    </a>
                </div>
                <p class="text-gray-600">Vista completa en <a href="{{ route('ganado.index') }}" class="text-agro-green font-semibold">Gestión de Ganado</a></p>
            </div>

            <div id="tab-actualizaciones" class="tab-content hidden">
                <p class="text-gray-600">Contenido de Actualizaciones - <a href="{{ route('actualizaciones.index') }}" class="text-agro-green font-semibold">Ver todas</a></p>
            </div>

            <div id="tab-clima" class="tab-content hidden">
                <p class="text-gray-600">Contenido de Clima - <a href="{{ route('clima.index') }}" class="text-agro-green font-semibold">Ver registros</a></p>
            </div>

            <div id="tab-cosechas" class="tab-content hidden">
                <p class="text-gray-600">Contenido de Cosechas - <a href="{{ route('cosechas.index') }}" class="text-agro-green font-semibold">Ver todas</a></p>
            </div>

            <div id="tab-ventas" class="tab-content hidden">
                <p class="text-gray-600">Contenido de Ventas - <a href="{{ route('ventas.index') }}" class="text-agro-green font-semibold">Ver todas</a></p>
            </div>

            <!-- Added Predicciones tab content -->
            <div id="tab-predicciones" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Predicciones de Semillas</h3>
                    <a href="{{ route('predicciones.calcular') }}" class="px-4 py-2 bg-agro-green text-white rounded-lg hover:bg-green-700 transition font-medium">
                        Nueva Predicción
                    </a>
                </div>
                <p class="text-gray-600">Sistema de predicción inteligente para optimizar la selección de semillas - <a href="{{ route('predicciones.index') }}" class="text-agro-green font-semibold">Ver predicciones</a></p>
            </div>

            <div id="tab-indicadores" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Análisis de Indicadores (IDC)</h3>
                <p class="text-gray-600 mb-4">Fórmula: <code class="bg-gray-100 px-2 py-1 rounded">IDC = (0.50×Rendimiento + 0.20×Oportunidad + 0.20×Calidad + 0.10×Registro) × FactorClima</code></p>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-green-600 rounded"></div>
                        <span class="text-sm"><span class="font-semibold">≥90:</span> Excelente</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-green-400 rounded"></div>
                        <span class="text-sm"><span class="font-semibold">80-89:</span> Bueno</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-yellow-400 rounded"></div>
                        <span class="text-sm"><span class="font-semibold">60-79:</span> En Riesgo</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-red-400 rounded"></div>
                        <span class="text-sm"><span class="font-semibold">&lt;60:</span> Crítico</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
    function showTab(tabName) {
        // Ocultar todos los contenidos
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Resetear estilos de botones
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('text-agro-green', 'border-b-2', 'border-agro-green');
            btn.classList.add('text-gray-500');
        });
        
        // Mostrar tab seleccionado
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Activar botón
        event.target.classList.remove('text-gray-500');
        event.target.classList.add('text-agro-green', 'border-b-2', 'border-agro-green');
    }
</script>
@endpush
@endsection
