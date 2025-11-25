@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('cultivos.show', $cultivo) }}" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Cultivo
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Editar Cultivo</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cultivos.update', $cultivo) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="finca_id" class="block text-gray-700 font-semibold mb-2">
                        Finca <span class="text-red-500">*</span>
                    </label>
                    <select name="finca_id" id="finca_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Seleccione una finca</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca->id }}" 
                                {{ old('finca_id', $cultivo->finca_id) == $finca->id ? 'selected' : '' }}>
                                {{ $finca->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 font-semibold mb-2">
                        Nombre del Cultivo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" 
                           value="{{ old('nombre', $cultivo->nombre) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-4">
                    <label for="variedad" class="block text-gray-700 font-semibold mb-2">
                        Variedad
                    </label>
                    <input type="text" name="variedad" id="variedad" 
                           value="{{ old('variedad', $cultivo->variedad) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label for="hectareas" class="block text-gray-700 font-semibold mb-2">
                        Área (hectáreas) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="hectareas" id="hectareas" 
                           value="{{ old('hectareas', $cultivo->hectareas) }}"
                           step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-4">
                    <label for="fecha_siembra" class="block text-gray-700 font-semibold mb-2">
                        Fecha de Siembra <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="fecha_siembra" id="fecha_siembra" 
                           value="{{ old('fecha_siembra', $cultivo->fecha_siembra->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-4">
                    <label for="fecha_cosecha_estimada" class="block text-gray-700 font-semibold mb-2">
                        Fecha de Cosecha Estimada
                    </label>
                    <input type="date" name="fecha_cosecha_estimada" id="fecha_cosecha_estimada" 
                           value="{{ old('fecha_cosecha_estimada', $cultivo->fecha_cosecha_estimada?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label for="estado" class="block text-gray-700 font-semibold mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="estado" id="estado" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="planificado" {{ old('estado', $cultivo->estado) == 'planificado' ? 'selected' : '' }}>Planificado</option>
                        <option value="sembrado" {{ old('estado', $cultivo->estado) == 'sembrado' ? 'selected' : '' }}>Sembrado</option>
                        <option value="desarrollo" {{ old('estado', $cultivo->estado) == 'desarrollo' ? 'selected' : '' }}>En Desarrollo</option>
                        <option value="cosechado" {{ old('estado', $cultivo->estado) == 'cosechado' ? 'selected' : '' }}>Cosechado</option>
                        <option value="finalizado" {{ old('estado', $cultivo->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="notas" class="block text-gray-700 font-semibold mb-2">
                        Notas
                    </label>
                    <textarea name="notas" id="notas" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notas', $cultivo->notas) }}</textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Actualizar Cultivo
                    </button>
                    <a href="{{ route('cultivos.show', $cultivo) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection