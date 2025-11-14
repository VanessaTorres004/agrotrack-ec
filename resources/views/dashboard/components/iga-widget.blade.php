@php
    $igaData = App\Services\IGACalculator::calcular(auth()->id());
@endphp

<div class="bg-gradient-to-br from-green-600 to-yellow-500 rounded-xl shadow-xl p-8 text-white">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold">Índice Global AgroTrack (IGA)</h2>
            <p class="text-green-100 mt-1">Desempeño integral de tu operación agrícola</p>
        </div>
        <div class="bg-white bg-opacity-20 p-4 rounded-full">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
    </div>

    <div class="flex items-center justify-center mb-8">
        <div class="relative w-48 h-48">
            <svg class="transform -rotate-90 w-48 h-48">
                <circle cx="96" cy="96" r="88" stroke="currentColor" stroke-width="12" fill="transparent" class="text-white text-opacity-20"/>
                <circle cx="96" cy="96" r="88" stroke="currentColor" stroke-width="12" fill="transparent" 
                    stroke-dasharray="{{ 2 * 3.14159 * 88 }}" 
                    stroke-dashoffset="{{ 2 * 3.14159 * 88 * (1 - $igaData['iga'] / 100) }}"
                    class="text-white transition-all duration-1000" 
                    stroke-linecap="round"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-5xl font-bold">{{ $igaData['iga'] }}</span>
                <span class="text-xl">/100</span>
                <span class="text-sm font-semibold mt-2 px-3 py-1 bg-white bg-opacity-20 rounded-full">
                    {{ $igaData['nivel'] }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white bg-opacity-10 rounded-lg p-4 backdrop-blur-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium">IDC Promedio</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $igaData['idc_promedio'] }}</p>
            <p class="text-xs text-green-100 mt-1">Salud de cultivos</p>
        </div>

        <div class="bg-white bg-opacity-10 rounded-lg p-4 backdrop-blur-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium">ISA</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $igaData['isa'] }}</p>
            <p class="text-xs text-green-100 mt-1">Sanidad animal</p>
        </div>

        <div class="bg-white bg-opacity-10 rounded-lg p-4 backdrop-blur-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium">Eficiencia Maq.</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $igaData['eficiencia_maquinaria'] }}%</p>
            <p class="text-xs text-green-100 mt-1">Operatividad</p>
        </div>
    </div>

    <div class="mt-6 p-4 bg-white bg-opacity-10 rounded-lg backdrop-blur-sm">
        <p class="text-sm">
            <strong>Nota:</strong> El IGA combina el desempeño agrícola (IDC 40%), sanitario (ISA 30%) y operativo (Maquinaria 30%) 
            para brindarte una visión integral del estado de tu operación.
        </p>
    </div>
</div>
