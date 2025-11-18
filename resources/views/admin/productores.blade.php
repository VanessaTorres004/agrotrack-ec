@extends('layouts.app')

@section('title', 'Productores - Admin AgroTrack EC')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Gestión de Productores</h2>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
            Volver al Dashboard
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-agro-green">
            <p class="text-gray-500 text-sm font-medium">Total Productores</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $productores->count() }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm font-medium">Total Fincas</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $productores->sum('fincas') }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-agro-yellow">
            <p class="text-gray-500 text-sm font-medium">Total Cultivos</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $productores->sum('cultivos') }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-600">
            <p class="text-gray-500 text-sm font-medium">Promedio IDC General</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($productores->avg('promedio_idc'), 1) }}</p>
        </div>
    </div>

    <!-- Tabla de Productores -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-agro-bg">
                    <tr class="text-left">
                        <th class="px-6 py-4 font-semibold text-gray-700">ID</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Nombre</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Fincas</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Cultivos</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Promedio IDC</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($productores as $productor)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-700">{{ $productor['id'] }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800">{{ $productor['nombre'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $productor['email'] }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700">
                                {{ $productor['fincas'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">
                                {{ $productor['cultivos'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-lg 
                                @if($productor['promedio_idc'] >= 80) text-green-600
                                @elseif($productor['promedio_idc'] >= 60) text-yellow-600
                                @else text-red-600
                                @endif
                            ">
                                {{ number_format($productor['promedio_idc'], 1) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($productor['estado'] === 'Excelente') bg-green-100 text-green-700
                                @elseif($productor['estado'] === 'Bueno') bg-blue-100 text-blue-700
                                @else bg-yellow-100 text-yellow-700
                                @endif
                            ">
                                {{ $productor['estado'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No hay productores registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
