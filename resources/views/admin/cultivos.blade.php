@extends('layouts.app')

@section('title', 'Cultivos - Admin AgroTrack EC')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Gestión de Cultivos</h2>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
            Volver al Dashboard
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-agro-green">
            <p class="text-gray-500 text-sm font-medium">Total Cultivos</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $cultivos->count() }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm font-medium">Total Hectáreas</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($cultivos->sum('hectareas'), 2) }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-600">
            <p class="text-gray-500 text-sm font-medium">Cultivos Activos</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $cultivos->where('estado', 'activo')->count() }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-agro-yellow">
            <p class="text-gray-500 text-sm font-medium">IDC Promedio</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">
                {{ $cultivos->where('idc', '!=', 'N/A')->avg('idc') ? number_format($cultivos->where('idc', '!=', 'N/A')->avg('idc'), 1) : 'N/A' }}
            </p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar por nombre</label>
                <input type="text" id="searchNombre" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agro-green focus:border-transparent" placeholder="Buscar cultivo...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select id="filterEstado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agro-green focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="cosechado">Cosechado</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Clasificación IDC</label>
                <select id="filterClasificacion" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agro-green focus:border-transparent">
                    <option value="">Todas</option>
                    <option value="Excelente">Excelente</option>
                    <option value="Bueno">Bueno</option>
                    <option value="En Riesgo">En Riesgo</option>
                    <option value="Crítico">Crítico</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="resetFilters()" class="w-full px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Cultivos -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="cultivosTable">
                <thead class="bg-agro-bg">
                    <tr class="text-left">
                        <th class="px-6 py-4 font-semibold text-gray-700">ID</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Cultivo</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Variedad</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Finca</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Productor</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Hectáreas</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">IDC</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Clasificación</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Fecha Siembra</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($cultivos as $cultivo)
                    <tr class="hover:bg-gray-50 transition" data-estado="{{ $cultivo['estado'] }}" data-clasificacion="{{ $cultivo['clasificacion'] }}">
                        <td class="px-6 py-4 text-gray-700">{{ $cultivo['id'] }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800">{{ $cultivo['nombre'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $cultivo['variedad'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $cultivo['finca'] }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $cultivo['productor'] }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium">{{ number_format($cultivo['hectareas'], 2) }} ha</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($cultivo['estado'] === 'activo') bg-green-100 text-green-700
                                @elseif($cultivo['estado'] === 'cosechado') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700
                                @endif
                            ">
                                {{ ucfirst($cultivo['estado']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-lg">{{ is_numeric($cultivo['idc']) ? number_format($cultivo['idc'], 1) : $cultivo['idc'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($cultivo['clasificacion'] === 'Excelente') bg-green-100 text-green-700
                                @elseif($cultivo['clasificacion'] === 'Bueno') bg-blue-100 text-blue-700
                                @elseif($cultivo['clasificacion'] === 'En Riesgo') bg-yellow-100 text-yellow-700
                                @elseif($cultivo['clasificacion'] === 'Crítico') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
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
                            No hay cultivos registrados
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
