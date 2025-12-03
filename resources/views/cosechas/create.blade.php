@extends('layouts.app')

@section('title', 'Registrar Cosecha - AgroTrack')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-primary-600 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-3xl font-bold text-gray-900">Registrar Cosecha</h2>
        </div>
        <p class="text-gray-600">Documenta los resultados de tu cosecha para calcular el rendimiento y calidad</p>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-primary-600 px-6 py-4">
            <h3 class="text-white font-semibold flex items-center">
                <i class="fas fa-shopping-basket mr-2"></i>
                Información de la Cosecha
            </h3>
        </div>
        
        <form action="{{ route('cosechas.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Cultivo Selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-leaf text-primary-600 mr-1"></i>
                    Cultivo *
                </label>
                <select name="cultivo_id" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <option value="">Seleccione un cultivo</option>
                    @foreach($cultivos as $cultivo)
                    <option value="{{ $cultivo->id }}" {{ (old('cultivo_id') ?? request('cultivo_id')) == $cultivo->id ? 'selected' : '' }}>
                        {{ $cultivo->nombre }} - {{ $cultivo->finca->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('cultivo_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Grid for Two Columns -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fecha de Cosecha -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-primary-600 mr-1"></i>
                        Fecha de Cosecha *
                    </label>
                    <!-- Changed field name from fecha_cosecha_real to fecha_cosecha to match controller validation -->
                    <!-- Changed field name from fecha_cosecha_real to fecha_cosecha to match database column -->
                    <input type="date" name="fecha_cosecha" value="{{ old('fecha_cosecha', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    @error('fecha_cosecha')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Cantidad en KG -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-weight text-primary-600 mr-1"></i>
                        Cantidad Cosechada (kg) *
                    </label>
                    <input type="number" step="0.01" name="cantidad_kg" value="{{ old('cantidad_kg') }}" required
                           placeholder="Ej: 250.50"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    @error('cantidad_kg')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Added mermas field for quality calculation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Calidad -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-star text-primary-600 mr-1"></i>
                        Calidad *
                    </label>
                    <select name="calidad" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">Seleccione la calidad</option>
                        <option value="excelente" {{ old('calidad') == 'excelente' ? 'selected' : '' }}>Excelente (90-100%)</option>
                        <option value="buena" {{ old('calidad') == 'buena' ? 'selected' : '' }}>Buena (70-89%)</option>
                        <option value="regular" {{ old('calidad') == 'regular' ? 'selected' : '' }}>Regular (50-69%)</option>
                        <option value="mala" {{ old('calidad') == 'mala' ? 'selected' : '' }}>Mala (0-49%)</option>
                    </select>
                    @error('calidad')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Mermas (%) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-exclamation-triangle text-primary-600 mr-1"></i>
                        Mermas (%) *
                    </label>
                    <input type="number" step="0.01" min="0" max="100" name="mermas" value="{{ old('mermas', 0) }}" required
                           placeholder="Ej: 5.00"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <p class="text-xs text-gray-500 mt-1">Porcentaje de producto perdido o desechado (0-100%)</p>
                    @error('mermas')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Grid for Two Columns -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Precio por KG -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign text-primary-600 mr-1"></i>
                        Precio por Kg *
                    </label>
                    <input type="number" step="0.01" name="precio_kg" value="{{ old('precio_kg') }}" required
                           placeholder="Ej: 2.50"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    @error('precio_kg')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Notas -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-sticky-note text-primary-600 mr-1"></i>
                    Notas Adicionales
                </label>
                <textarea name="notas" rows="4" 
                          placeholder="Observaciones sobre la cosecha, condiciones, etc..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">{{ old('notas') }}</textarea>
                @error('notas')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Info Box -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-green-600 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-semibold text-green-900">Impacto en el IDC</p>
                        <p class="text-sm text-green-700 mt-1">
                            Esta cosecha afectará directamente los indicadores de <strong>Rendimiento</strong> (cantidad cosechada vs. esperada), 
                            <strong>Oportunidad</strong> (fecha de cosecha vs. fecha estimada) y <strong>Calidad</strong> (calidad y mermas registradas).
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ url()->previous() }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md">
                    <i class="fas fa-check mr-2"></i>
                    Registrar Cosecha
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
