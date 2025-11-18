@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🤖 Predicción Inteligente de Semillas</h1>
            <p class="mt-2 text-gray-600">IA agrícola que predice cuántas semillas necesitarás</p>
        </div>

        <!-- Formulario de Predicción -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold mb-6 text-gray-900">Calcular Predicción</h2>
            
            <form id="prediccionForm" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cultivo 🌾</label>
                        <select name="cultivo_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Seleccionar cultivo</option>
                            @foreach($cultivos as $cultivo)
                            <option value="{{ $cultivo->id }}">{{ $cultivo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Área de Siembra (hectáreas)</label>
                        <input type="number" name="area_hectareas" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="10.5">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Temperatura Promedio (°C) - Opcional</label>
                        <input type="number" name="temperatura_promedio" step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="25">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Humedad Promedio (%) - Opcional</label>
                        <input type="number" name="humedad_promedio" step="0.1" min="0" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="70">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">pH del Suelo - Opcional</label>
                        <input type="number" name="ph_suelo" step="0.1" min="0" max="14" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="6.5">
                    </div>
                </div>

                <button type="submit" id="btnCalcular" class="w-full bg-gradient-to-r from-green-600 to-yellow-500 hover:from-green-700 hover:to-yellow-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:scale-105 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Calcular Predicción de Semillas
                </button>
            </form>

            <!-- Resultado de Predicción -->
            <div id="resultadoPrediccion" class="hidden mt-8 p-6 bg-gradient-to-r from-yellow-50 to-green-50 rounded-lg border-2 border-yellow-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Resultado de Predicción</h3>
                        <p id="mensajeResultado" class="text-lg text-gray-800 mb-2"></p>
                        <p id="ahorroResultado" class="text-md text-green-700 font-semibold"></p>
                        <div id="indicadorConfianza" class="mt-4"></div>
                        
                        <div class="mt-6 p-4 bg-white rounded-lg border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-2">Factores Considerados:</h4>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>✓ Promedio de uso histórico</li>
                                <li>✓ Desperdicio promedio</li>
                                <li>✓ Factor climático mensual</li>
                                <li>✓ Área de siembra</li>
                            </ul>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-gray-900 mb-2">Fórmula Utilizada:</h4>
                            <p class="text-sm text-gray-700 font-mono">Predicción = Área × Densidad × UsoPromedio × FactorClima × (1 + Desperdicio)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Predicciones -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Historial de Predicciones</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cultivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área (ha)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semillas Predichas (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ahorro Estimado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confianza</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($predicciones as $prediccion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $prediccion->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $prediccion->cultivo->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($prediccion->area_hectareas, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format($prediccion->paquetes_predichos, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                {{ $prediccion->ahorro_estimado_porcentaje ? number_format($prediccion->ahorro_estimado_porcentaje, 1) . '%' : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($prediccion->nivel_confianza === 'estable')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">🟢 Estable</span>
                                @elseif($prediccion->nivel_confianza === 'variable')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">🟡 Variable</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🔴 Riesgo</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay predicciones registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.getElementById('prediccionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btnCalcular = document.getElementById('btnCalcular');
    const btnTextoOriginal = btnCalcular.innerHTML;
    
    // Deshabilitar botón y mostrar loading
    btnCalcular.disabled = true;
    btnCalcular.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Calculando...';
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    console.log('Datos enviados:', data);
    
    try {
        const response = await fetch('{{ route("predicciones.calcular") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        console.log('Status de respuesta:', response.status);
        
        // Verificar si la respuesta es JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new Error("La respuesta no es JSON. Probablemente hay un error en el servidor.");
        }
        
        const result = await response.json();
        console.log('Respuesta del servidor:', result);
        
        // Verificar si hubo éxito
        if (response.ok && result.success) {
            // Mostrar mensaje
            document.getElementById('mensajeResultado').textContent = result.mensaje;
            
            // Mostrar ahorro
            const ahorro = parseFloat(result.prediccion.ahorro_estimado_porcentaje);
            if (ahorro && ahorro > 0) {
                document.getElementById('ahorroResultado').textContent = 
                    `Ahorro estimado: ${ahorro.toFixed(1)}% frente al ciclo anterior`;
            } else {
                document.getElementById('ahorroResultado').textContent = 
                    'Primera predicción para este cultivo';
            }
            
            // Mostrar nivel de confianza
            const confianza = result.prediccion.nivel_confianza;
            let confianzaHTML = '';
            if (confianza === 'estable') {
                confianzaHTML = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">🟢 Predicción estable</span>';
            } else if (confianza === 'variable') {
                confianzaHTML = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">🟡 Margen variable</span>';
            } else {
                confianzaHTML = '<span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">🔴 Riesgo climático</span>';
            }
            document.getElementById('indicadorConfianza').innerHTML = confianzaHTML;
            
            // Mostrar resultado
            document.getElementById('resultadoPrediccion').classList.remove('hidden');
            
            // Recargar página después de 3 segundos
            setTimeout(() => {
                window.location.reload();
            }, 3000);
            
        } else {
            // Error del servidor
            throw new Error(result.mensaje || 'Error desconocido al calcular la predicción');
        }
        
    } catch (error) {
        console.error('Error capturado:', error);
        alert('Error: ' + error.message);
    } finally {
        // Restaurar botón
        btnCalcular.disabled = false;
        btnCalcular.innerHTML = btnTextoOriginal;
    }
});
</script>
@endpush
@endsection