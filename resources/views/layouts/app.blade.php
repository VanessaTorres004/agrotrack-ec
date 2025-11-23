<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AgroTrack EC')</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Colores corporativos --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'agro-primary': '#2F6B32',
                        'agro-primary-light': '#3C8D40',
                        'agro-accent': '#79C86E',
                        'agro-sand': '#F1EFE7',
                        'agro-dark': '#1F1F1F'
                    }
                }
            }
        }
    </script>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Chart.js + Lucide --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar links */
        .sidebar-link {
            @apply flex items-center gap-3 px-4 py-2 rounded-lg text-white/90 hover:bg-white/10 transition cursor-pointer;
        }
        .sidebar-link.active {
            @apply bg-white/20 text-white font-semibold;
        }
    </style>
</head>

<body class="bg-agro-sand min-h-screen">

<div class="flex h-screen overflow-hidden">

    <!-- ===================================================== -->
    <!-- 🌿 SIDEBAR PREMIUM — MANTENIENDO TU ESTRUCTURA ACTUAL -->
    <!-- ===================================================== -->
        <aside class="w-64 bg-gradient-to-b 
            from-[#2F6B32] 
            via-[#3C8D40] 
            to-[#4FAF5A] 
            text-white hidden lg:flex flex-col shadow-xl">

        <!-- LOGO -->
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-md">
                    <i data-lucide="sprout" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide">AgroTrack EC</h1>
                    <p class="text-xs text-white/70">Sistema Agrícola</p>
                </div>
            </div>
            
        </div>

        <!-- MENÚ -->
        
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

            @php
                $menuClasses = "flex items-center gap-3 px-4 py-2 rounded-lg cursor-pointer transition
                                text-white hover:bg-white/10";
                $active = "bg-white/20 font-semibold";
            @endphp

            @if(auth()->user()->isAdmin())

                <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? $active : '' }} {{ $menuClasses }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.productores') }}"
                class="{{ request()->routeIs('admin.productores') ? $active : '' }} {{ $menuClasses }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Productores</span>
                </a>

                <a href="{{ route('admin.cultivos') }}"
                class="{{ request()->routeIs('admin.cultivos') ? $active : '' }} {{ $menuClasses }}">
                    <i data-lucide="wheat" class="w-5 h-5"></i>
                    <span>Cultivos</span>
                </a>


                <a href="{{ route('ganado.index') }}"
                class="{{ request()->routeIs('ganado.*') ? $active : '' }} {{ $menuClasses }}">

                    <!-- Ícono SVG de Ganado (NO Lucide, NO Emoji) -->
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        class="w-5 h-5 text-white"
                    >
                        <path d="M8 3H5L3 7v4a9 9 0 009 9v0a9 9 0 009-9V7l-2-4h-3" />
                        <path d="M7 10h.01" />
                        <path d="M17 10h.01" />
                    </svg>

                    <span>Ganado</span>
                </a>

                </a>

                <a href="{{ route('maquinaria.index') }}"
                class="{{ request()->routeIs('maquinaria.*') ? $active : '' }} {{ $menuClasses }}">
                    <i data-lucide="tractor" class="w-5 h-5"></i>
                    <span>Maquinaria</span>
                </a>

                <a href="{{ route('clima.index') }}"
                class="{{ request()->routeIs('clima.*') ? $active : '' }} {{ $menuClasses }}">
                    <i data-lucide="cloud" class="w-5 h-5"></i>
                    <span>Clima</span>
                </a>

                <a href="{{ route('predicciones.index') }}"
                class="{{ request()->routeIs('predicciones.*') ? $active : '' }} {{ $menuClasses }}">
                    <i data-lucide="brain" class="w-5 h-5"></i>
                    <span>Predicciones</span>
                </a>

                <a href="{{ route('admin.reportes') }}"
                class="{{ request()->routeIs('admin.reportes') ? $active : '' }} {{ $menuClasses }}">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>Reportes</span>
                </a>

            @endif
        </nav>


        <!-- Cerrar sesión -->
        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- ============================= -->
    <!-- 🌤️ NAVBAR SUPERIOR PREMIUM -->
    <!-- ============================= -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="bg-white/90 backdrop-blur-md border-b shadow-sm sticky top-0 z-30">
            <div class="px-6 py-4 flex justify-between items-center">

                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                        <i data-lucide="menu" class="w-6 h-6 text-agro-dark"></i>
                    </button>

                    <div>
                        <h2 class="text-xl font-semibold text-agro-dark">@yield('page-title')</h2>
                        <p class="text-xs text-gray-500">@yield('page-subtitle')</p>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <button class="relative p-2 rounded-xl hover:bg-gray-100 transition">
                        <i data-lucide="bell" class="w-5 h-5 text-agro-dark"></i>
                        @if(isset($alertasActivas) && $alertasActivas > 0)
                            <span class="absolute top-1 right-1 bg-red-500 w-2 h-2 rounded-full"></span>
                        @endif
                    </button>

                    <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                        <div class="w-10 h-10 bg-agro-primary rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden md:block">
                            <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
                        </div>
                    </div>

                </div>

            </div>
        </header>

        <!-- CONTENIDO -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-10">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')

<script>
    lucide.createIcons();
</script>

</body>
</html>
