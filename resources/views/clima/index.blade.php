@extends('layouts.app')

@section('title', 'Clima - AgroTrack EC')
@section('page-title', 'Registros Climáticos')
@section('page-subtitle', 'Historial de condiciones climáticas')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-semibold text-agro-dark">Clima</h2>
            <p class="text-gray-600 mt-1">Historial de registros climáticos por finca</p>
        </div>
        <a href="{{ route('clima.create') }}" class="btn-primary flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nuevo Registro
        </a>
    </div>

    <!-- TABLA -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-agro-sand">
            <h3 class="text-xl font-semibold text-agro-dark">Registros Climáticos</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="table-modern min-w-full">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Finca</th>
                        <th>Temp Min</th>
                        <th>Temp Max</th>
                        <th>Precipitación (mm)</th>
                        <th>Humedad (%)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $r)
                        <tr>
                            <td>{{ $r->fecha }}</td>
                            <td>{{ $r->finca->nombre }}</td>
                            <td>{{ $r->temperatura_min }}°C</td>
                            <td>{{ $r->temperatura_max }}°C</td>
                            <td>{{ $r->precipitacion_mm }}</td>
                            <td>{{ $r->humedad_relativa }}%</td>
                            <td class="space-x-3">
                                <a href="{{ route('clima.edit', $r) }}" class="text-blue-600 hover:text-blue-800">Editar</a>
                                <form action="{{ route('clima.destroy', $r) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">
                                <i data-lucide="cloud-drizzle" class="w-10 h-10 mx-auto text-gray-400"></i>
                                <p>No hay registros climáticos aún.</p>
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
