@extends('layouts.app')

@section('title', 'Mis Cosechas - AgroTrack')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Historial de Cosechas</h2>
                <p class="text-gray-600 mt-1">Registro de todas tus cosechas y sus resultados</p>
            </div>
            <a href="{{ route('cosechas.create') }}" 
               class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md">
                <i class="fas fa-plus mr-2"></i>
                Nueva Cosecha
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if($cosechas->count() > 0)
    <!-- Cosechas Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-primary-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Cultivo</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Finca</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Fecha</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Cantidad</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Calidad</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Mermas</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Precio/kg</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Total</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($cosechas as $cosecha)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-leaf text-primary-600 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ $cosecha->cultivo->nombre }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $cosecha->cultivo->finca->nombre }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ \Carbon\Carbon::parse($cosecha->fecha_cosecha_real)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-900">{{ number_format($cosecha->cantidad_kg, 2) }} kg</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $calidadColors = [
                                    'excelente' => 'bg-green-100 text-green-800',
                                    'buena' => 'bg-blue-100 text-blue-800',
                                    'regular' => 'bg-yellow-100 text-yellow-800',
                                    'mala' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $calidadColors[$cosecha->calidad] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($cosecha->calidad) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            @if($cosecha->mermas > 0)
                                <span class="text-red-600 font-medium">{{ number_format($cosecha->mermas, 1) }}%</span>
                            @else
                                <span class="text-green-600">0%</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            ${{ number_format($cosecha->precio_kg ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-primary-600">
                                ${{ number_format(($cosecha->cantidad_kg * ($cosecha->precio_kg ?? 0)), 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('cultivos.show', $cosecha->cultivo_id) }}" 
                                   class="text-primary-600 hover:text-primary-700 transition"
                                   title="Ver cultivo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cosechas.edit', $cosecha->id) }}" 
                                   class="text-blue-600 hover:text-blue-700 transition"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('cosechas.destroy', $cosecha->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar esta cosecha?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 transition"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($cosechas->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $cosechas->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Cosechado</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ number_format($cosechas->sum('cantidad_kg'), 2) }} kg
                    </p>
                </div>
                <div class="bg-primary-100 p-3 rounded-lg">
                    <i class="fas fa-weight text-primary-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Ingreso Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        ${{ number_format($cosechas->sum(fn($c) => $c->cantidad_kg * ($c->precio_kg ?? 0)), 2) }}
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Promedio Mermas</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ number_format($cosechas->avg('mermas') ?? 0, 1) }}%
                    </p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Cosechas</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $cosechas->total() }}
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-shopping-basket text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-md p-12 text-center">
        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-shopping-basket text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No hay cosechas registradas</h3>
        <p class="text-gray-600 mb-6">Comienza registrando tu primera cosecha para llevar el control de tu producción</p>
        <a href="{{ route('cosechas.create') }}" 
           class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Registrar Primera Cosecha
        </a>
    </div>
    @endif
</div>
@endsection
