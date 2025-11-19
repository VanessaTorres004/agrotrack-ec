@extends('layouts.app')

@section('title', 'Productores - Admin AgroTrack EC')
@section('page-title', 'Gestión de Productores')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-semibold text-agro-dark">Gestión de Productores</h2>
            <p class="text-gray-600 mt-1">Administración de productores y fincas</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver al Dashboard
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card stat-card-primary">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Productores</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $productores->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-agro-primary rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Fincas</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $productores->sum('fincas') }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-agro-accent">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Cultivos</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $productores->sum('cultivos') }}</p>
                </div>
                <div class="w-12 h-12 bg-agro-accent rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="wheat" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Promedio IDC General</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ number_format($productores->avg('promedio_idc'), 1) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Productores -->
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">ID</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Nombre</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Email</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Fincas</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Cultivos</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Promedio IDC</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productores as $productor)
                    <tr>
                        <td class="px-6 py-4 text-gray-700">{{ $productor['id'] }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-agro-dark">{{ $productor['nombre'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $productor['email'] }}</td>
                        <td class="px-6 py-4">
                            <span class="badge badge-info">
                                {{ $productor['fincas'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge badge-success">
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
                            <span class="badge
                                @if($productor['estado'] === 'Excelente') badge-success
                                @elseif($productor['estado'] === 'Bueno') badge-info
                                @else badge-warning
                                @endif
                            ">
                                {{ $productor['estado'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
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

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection
