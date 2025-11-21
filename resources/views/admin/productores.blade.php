@extends('layouts.app')

@section('title', 'Productores - Admin AgroTrack EC')
@section('page-title', 'Gestión de Productores')

@section('content')
<div class="min-h-screen bg-agro-sand py-6">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">👨‍🌾 Gestión de Productores</h1>
            <p class="mt-2 text-gray-600">Administración de productores y actividades registradas</p>
        </div>

        {{-- TARJETAS RESUMEN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            {{-- Total Productores --}}
            <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Total Productores</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $productores->count() }}</p>
                </div>
                <div class="p-3 bg-agro-primary/15 text-agro-primary rounded-full">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
            </div>

            {{-- Total Fincas --}}
            <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Total Fincas</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $productores->sum('fincas') }}</p>
                </div>
                <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                    <i data-lucide="map-pin" class="w-7 h-7"></i>
                </div>
            </div>

            {{-- Total Cultivos --}}
            <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Cultivos Registrados</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $productores->sum('cultivos') }}</p>
                </div>
                <div class="p-3 bg-agro-accent/20 text-agro-accent rounded-full">
                    <i data-lucide="wheat" class="w-7 h-7"></i>
                </div>
            </div>

            {{-- IDC Promedio --}}
            <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Promedio IDC General</p>
                    <p class="text-3xl font-bold text-agro-primary mt-2">
                        {{ number_format($productores->avg('promedio_idc'), 1) }}
                    </p>
                </div>
                <div class="p-3 bg-green-100 text-green-600 rounded-full">
                    <i data-lucide="trending-up" class="w-7 h-7"></i>
                </div>
            </div>

        </div>

        {{-- TABLA PRINCIPAL --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            
            {{-- Header tabla --}}
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Listado de Productores</h2>
            </div>

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="th">ID</th>
                            <th class="th">Nombre</th>
                            <th class="th">Email</th>
                            <th class="th">Fincas</th>
                            <th class="th">Cultivos</th>
                            <th class="th">Promedio IDC</th>
                            <th class="th">Estado</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                        @forelse($productores as $productor)
                        <tr>

                            {{-- ID --}}
                            <td class="td">{{ $productor['id'] }}</td>

                            {{-- Nombre --}}
                            <td class="td font-semibold text-gray-900 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-agro-primary/20 text-agro-primary flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($productor['nombre'], 0, 1)) }}
                                </div>
                                {{ $productor['nombre'] }}
                            </td>

                            {{-- Email --}}
                            <td class="td text-gray-600">{{ $productor['email'] }}</td>

                            {{-- Fincas --}}
                            <td class="td">
                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                    {{ $productor['fincas'] }}
                                </span>
                            </td>

                            {{-- Cultivos --}}
                            <td class="td">
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    {{ $productor['cultivos'] }}
                                </span>
                            </td>

                            {{-- Promedio IDC --}}
                            <td class="td font-semibold">
                                <span class="@if($productor['promedio_idc'] >= 80) text-green-600
                                             @elseif($productor['promedio_idc'] >= 60) text-yellow-600
                                             @else text-red-600 @endif">
                                    {{ number_format($productor['promedio_idc'], 1) }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="td">
                                @php
                                    $estado = $productor['estado'];
                                    $color = match($estado) {
                                        'Excelente' => 'bg-green-100 text-green-700',
                                        'Bueno'     => 'bg-agro-accent/20 text-agro-accent',
                                        default     => 'bg-yellow-100 text-yellow-700',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ $estado }}
                                </span>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="inbox" class="w-12 h-12 text-gray-400"></i>
                                    <p>No hay productores registrados</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ESTILOS TH/TD GLOBAL --}}
<style>
    .th {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide;
    }
    .td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-gray-700;
    }
</style>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush

@endsection
