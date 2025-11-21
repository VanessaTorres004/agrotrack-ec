@extends('layouts.app')

@section('title', 'Cultivos - Admin AgroTrack EC')
@section('page-title', 'Gestión de Cultivos')

@section('content')
<div class="min-h-screen bg-agro-sand py-6">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🌾 Gestión de Cultivos</h1>
            <p class="mt-2 text-gray-600">Administración, monitoreo y clasificación de cultivos</p>
        </div>

        {{-- TARJETAS RESUMEN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            {{-- Total --}}
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total de Cultivos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $cultivos->count() }}</p>
                    </div>
                    <div class="p-3 bg-agro-primary/15 text-agro-primary rounded-full">
                        <i data-lucide="wheat" class="w-7 h-7"></i>
                    </div>
                </div>
            </div>

            {{-- Hectáreas --}}
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Hectáreas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($cultivos->sum('hectareas'), 2) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                        <i data-lucide="map" class="w-7 h-7"></i>
                    </div>
                </div>
            </div>

            {{-- Activos --}}
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Cultivos Activos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ $cultivos->where('estado', 'activo')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 text-green-600 rounded-full">
                        <i data-lucide="power" class="w-7 h-7"></i>
                    </div>
                </div>
            </div>

            {{-- IDC --}}
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">IDC Promedio</p>
                        <p class="text-3xl font-bold text-agro-primary mt-2">
                            {{ $cultivos->avg('idc') ? number_format($cultivos->avg('idc'),1) : 'N/A' }}
                        </p>
                    </div>
                    <div class="p-3 bg-agro-primary/15 text-agro-primary rounded-full">
                        <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- FILTROS --}}
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Filtros</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Buscar por Nombre</label>
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
                        <input id="searchNombre" type="text"
                            class="pl-10 input-modern"
                            placeholder="Buscar cultivo...">
                    </div>
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                    <select id="filterEstado" class="input-modern">
                        <option value="">Todos</option>
                        <option value="activo">Activo</option>
                        <option value="cosechado">Cosechado</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                {{-- Clasificación --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Clasificación IDC</label>
                    <select id="filterClasificacion" class="input-modern">
                        <option value="">Todas</option>
                        <option value="Excelente">Excelente</option>
                        <option value="Bueno">Bueno</option>
                        <option value="En Riesgo">En Riesgo</option>
                        <option value="Crítico">Crítico</option>
                    </select>
                </div>

                {{-- Reset --}}
                <div class="flex items-end">
                    <button onclick="resetFilters()"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium flex gap-2 justify-center">
                        <i data-lucide="x" class="w-4 h-4"></i> Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>

        {{-- TABLA PROFESIONAL --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Listado de Cultivos</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="cultivosTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="th">ID</th>
                            <th class="th">Cultivo</th>
                            <th class="th">Variedad</th>
                            <th class="th">Finca</th>
                            <th class="th">Productor</th>
                            <th class="th">Hectáreas</th>
                            <th class="th">Estado</th>
                            <th class="th">IDC</th>
                            <th class="th">Clasificación</th>
                            <th class="th">Fecha Siembra</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($cultivos as $cultivo)
                        <tr data-estado="{{ $cultivo['estado'] }}" data-clasificacion="{{ $cultivo['clasificacion'] }}">
                            <td class="td">{{ $cultivo['id'] }}</td>
                            <td class="td font-semibold text-gray-900">{{ $cultivo['nombre'] }}</td>
                            <td class="td text-gray-600">{{ $cultivo['variedad'] ?? 'N/A' }}</td>
                            <td class="td text-gray-700">{{ $cultivo['finca'] }}</td>
                            <td class="td text-gray-700">{{ $cultivo['productor'] }}</td>
                            <td class="td font-medium">{{ number_format($cultivo['hectareas'], 2) }} ha</td>

                            {{-- Estado --}}
                            <td class="td">
                                @php
                                    $stateColors = [
                                        'activo' => 'bg-green-100 text-green-700',
                                        'cosechado' => 'bg-blue-100 text-blue-700',
                                        'inactivo' => 'bg-gray-100 text-gray-600',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $stateColors[$cultivo['estado']] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($cultivo['estado']) }}
                                </span>
                            </td>

                            {{-- IDC --}}
                            <td class="td font-semibold text-agro-primary">
                                {{ is_numeric($cultivo['idc']) ? number_format($cultivo['idc'], 1) : $cultivo['idc'] }}
                            </td>

                            {{-- Clasificación --}}
                            <td class="td">
                                @php
                                    $classColors = [
                                        'Excelente' => 'bg-green-100 text-green-700',
                                        'Bueno' => 'bg-agro-accent/20 text-agro-accent',
                                        'En Riesgo' => 'bg-yellow-100 text-yellow-700',
                                        'Crítico' => 'bg-red-100 text-red-700'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $classColors[$cultivo['clasificacion']] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $cultivo['clasificacion'] }}
                                </span>
                            </td>

                            <td class="td text-gray-600">{{ $cultivo['fecha_siembra'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="inbox" class="w-12 h-12 text-gray-400"></i>
                                    <p>No hay cultivos registrados</p>
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

{{-- ESTILOS REUSABLES PARA LA TABLA --}}
<style>
    .th {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide;
    }
    .td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-gray-700;
    }
</style>

@push('scripts')
<script>
    lucide.createIcons();

    // === FILTROS ===
    const filterTable = () => {
        const search = document.getElementById('searchNombre').value.toLowerCase();
        const estado = document.getElementById('filterEstado').value.toLowerCase();
        const clasificacion = document.getElementById('filterClasificacion').value;

        document.querySelectorAll('#cultivosTable tbody tr').forEach(row => {

            const nombre = row.cells[1].innerText.toLowerCase();
            const rowEstado = row.dataset.estado.toLowerCase();
            const rowClasif = row.dataset.clasificacion;

            const matchNombre = nombre.includes(search);
            const matchEstado = !estado || rowEstado === estado;
            const matchClasif = !clasificacion || rowClasif === clasificacion;

            row.style.display = (matchNombre && matchEstado && matchClasif) ? '' : 'none';
        });
    };

    document.getElementById('searchNombre').addEventListener('input', filterTable);
    document.getElementById('filterEstado').addEventListener('change', filterTable);
    document.getElementById('filterClasificacion').addEventListener('change', filterTable);

    const resetFilters = () => {
        document.getElementById('searchNombre').value = '';
        document.getElementById('filterEstado').value = '';
        document.getElementById('filterClasificacion').value = '';
        filterTable();
    };
</script>
@endpush

@endsection
