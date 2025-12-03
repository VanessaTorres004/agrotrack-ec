@extends('layouts.app')

@section('title', 'Registrar Actividad - AgroTrack')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-primary-600 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-3xl font-bold text-gray-900">Registrar Actividad</h2>
        </div>
        <p class="text-gray-600">Documenta las actividades realizadas en tus cultivos (riego, fertilización, control de plagas, etc.)</p>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h3 class="text-white font-semibold flex items-center">
                <i class="fas fa-tasks mr-2"></i>
                Información de la Actividad
            </h3>
        </div>
        
        <form action="{{ route('actualizaciones.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Cultivo Selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-leaf text-primary-600 mr-1"></i>
                    Cultivo *
                </label>
                <select name="cultivo_id" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
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
                <!-- Tipo de Actividad -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-blue-600 mr-1"></i>
                        Tipo de Actividad *
                    </label>
                    <select name="tipo" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Seleccione tipo</option>
                        <option value="riego" {{ old('tipo') == 'riego' ? 'selected' : '' }}>Riego</option>
                        <option value="fertilizacion" {{ old('tipo') == 'fertilizacion' ? 'selected' : '' }}>Fertilización</option>
                        <option value="control_plagas" {{ old('tipo') == 'control_plagas' ? 'selected' : '' }}>Control de Plagas</option>
                        <option value="poda" {{ old('tipo') == 'poda' ? 'selected' : '' }}>Poda</option>
                        <option value="limpieza" {{ old('tipo') == 'limpieza' ? 'selected' : '' }}>Limpieza</option>
                        <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('tipo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Fecha -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-blue-600 mr-1"></i>
                        Fecha de la Actividad *
                    </label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('fecha')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Descripción -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-blue-600 mr-1"></i>
                    Descripción de la Actividad *
                </label>
                <textarea name="descripcion" rows="4" required
                          placeholder="Describe detalladamente la actividad realizada..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Importancia del registro</p>
                        <p class="text-sm text-blue-700 mt-1">
                            Cada actividad registrada mejora el cálculo del IDC. Un registro consistente aumenta el indicador de "Registro" 
                            y te ayuda a tomar mejores decisiones sobre tu cultivo.
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
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                    <i class="fas fa-check mr-2"></i>
                    Registrar Actividad
                </button>
            </div>
        </form>
    </div>
    
    <!-- Quick Tips -->
    <div class="mt-6 bg-white rounded-xl shadow-md p-6">
        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
            Consejos para un mejor registro
        </h4>
        <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <i class="fas fa-check text-primary-600 mr-2 mt-1"></i>
                <span>Registra las actividades el mismo día que las realizas</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-primary-600 mr-2 mt-1"></i>
                <span>Sé específico en la descripción: cantidad de agua, tipo de fertilizante, etc.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-primary-600 mr-2 mt-1"></i>
                <span>Mantén un registro constante para mejorar tu IDC</span>
            </li>
        </ul>
    </div>
</div>
@endsection
