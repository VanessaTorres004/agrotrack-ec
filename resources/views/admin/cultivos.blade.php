@extends('layouts.app')

@section('title', 'Cultivos - Admin AgroTrack EC')
@section('page-title', 'Gestión de Cultivos')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-semibold text-agro-dark">Gestión de Cultivos</h2>
            <p class="text-gray-600 mt-1">Administración y monitoreo de cultivos</p>
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
                    <p class="text-gray-500 text-sm font-semibold">Total Cultivos</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $cultivos->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-agro-primary rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="wheat" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Hectáreas</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ number_format($cultivos->sum('hectareas'), 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="map" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-agro-accent">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Cultivos Activos</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">{{ $cultivos->where('estado', 'activo')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-agro-accent rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold">IDC Promedio</p>
                    <p class="text-3xl font-bold text-agro-dark mt-2">
                        {{ $cultivos->where('idc', '!=', 'N/A')->avg('idc') ? number_format($cultivos->where('idc', '!=', 'N/A')->avg('idc'), 1) : 'N/A' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-agro-dark mb-2">Buscar por nombre</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="text" id="searchNombre" class="input-modern pl-10" placeholder="Buscar cultivo...">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-agro-dark mb-2">Estado</label>
                <select id="filterEstado" class="input-modern">
                    <option value="">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="cosechado">Cosechado</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-agro-dark mb-2">Clasificación IDC</label>
                <select id="filterClasificacion" class="input-modern">
                    <option value="">Todas</option>
                    <option value="Excelente">Excelente</option>
                    <option value="Bueno">Bueno</option>
                    <option value="En Riesgo">En Riesgo</option>
                    <option value="Crítico">Crítico</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="resetFilters()" class="w-full btn-secondary flex items-center justify-center gap-2">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Cultivos -->
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="table-modern" id="cultivosTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">ID</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Cultivo</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Variedad</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Finca</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Productor</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Hectáreas</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Estado</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">IDC</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Clasificación</th>
                        <th class="px-6 py-4 font-semibold text-agro-dark text-left">Fecha Siembra</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cultivos as $cultivo)
                    <tr class="hover:bg-gray-50 transition" data-estado="{{ $cultivo['estado'] }}" data-clasificacion="{{ $cultivo['clasificacion'] }}">
                        <td class="px-6 py-4 text-gray-700">{{ $cultivo['id'] }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-agro-dark">{{ $cultivo['nombre'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $cultivo['variedad'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $cultivo['finca'] }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $cultivo['productor'] }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium">{{ number_format($cultivo['hectareas'], 2) }} ha</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge
                                @if($cultivo['estado'] === 'activo') badge-success
                                @elseif($cultivo['estado'] === 'cosechado') badge-info
                                @else badge-warning
                                @endif
                            ">
                                {{ ucfirst($cultivo['estado']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-lg text-agro-primary">{{ is_numeric($cultivo['idc']) ? number_format($cultivo['idc'], 1) : $cultivo['idc'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge
                                @if($cultivo['clasificacion'] === 'Excelente') badge-success
                                @elseif($cultivo['clasificacion'] === 'Bueno') badge-info
                                @elseif($cultivo['clasificacion'] === 'En Riesgo') badge-warning
                                @elseif($cultivo['clasificacion'] === 'Crítico') badge-danger
                                @else badge-warning
                                @endif
                            ">
                                {{ $cultivo['clasificacion'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $cultivo['fecha_siembra'] }}</td>
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

@push('scripts')
<script>
    lucide.createIcons();
    
    // Simple client-side filtering
    document.getElementById('searchNombre').addEventListener('input', filterTable);
    document.getElementById('filterEstado').addEventListener('change', filterTable);
    document.getElementById('filterClasificacion').addEventListener('change', filterTable);

    function filterTable() {
        const searchNombre = document.getElementById('searchNombre').value.toLowerCase();
        const filterEstado = document.getElementById('filterEstado').value.toLowerCase();
        const filterClasificacion = document.getElementById('filterClasificacion').value;
        
        const rows = document.querySelectorAll('#cultivosTable tbody tr');
        
        rows.forEach(row => {
            if (!row.dataset.estado) return; // Skip empty row
            
            const nombre = row.cells[1].textContent.toLowerCase();
            const estado = row.dataset.estado.toLowerCase();
            const clasificacion = row.dataset.clasificacion;
            
            const matchNombre = nombre.includes(searchNombre);
            const matchEstado = !filterEstado || estado === filterEstado;
            const matchClasificacion = !filterClasificacion || clasificacion === filterClasificacion;
            
            if (matchNombre && matchEstado && matchClasificacion) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function resetFilters() {
        document.getElementById('searchNombre').value = '';
        document.getElementById('filterEstado').value = '';
        document.getElementById('filterClasificacion').value = '';
        filterTable();
    }
</script>
@endpush
@endsection
