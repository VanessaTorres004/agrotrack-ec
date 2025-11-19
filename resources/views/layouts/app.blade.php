<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AgroTrack EC')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'agro-primary': '#3C8D40',
                        'agro-accent': '#79C86E',
                        'agro-sand': '#F2F0E4',
                        'agro-dark': '#2F2F2F',
                        'agro-green': '#3C8D40',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', 'Nunito', 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-agro-sand min-h-screen">
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-agro-primary text-white flex-shrink-0 hidden lg:flex flex-col shadow-lg">
            <div class="p-6 border-b border-agro-accent/20">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-md">
                        <i data-lucide="sprout" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">AgroTrack EC</h1>
                        <p class="text-xs text-white/70">Sistema Agrícola</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                @if(auth()->user()->isAdmin())
                    <!-- Admin sidebar -->
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.productores') }}" class="sidebar-link {{ request()->routeIs('admin.productores') ? 'active' : '' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span class="font-semibold">Productores</span>
                    </a>
                    <a href="{{ route('admin.cultivos') }}" class="sidebar-link {{ request()->routeIs('admin.cultivos') ? 'active' : '' }}">
                        <i data-lucide="wheat" class="w-5 h-5"></i>
                        <span class="font-semibold">Cultivos</span>
                    </a>
                    <a href="{{ route('ganado.index') }}" class="sidebar-link {{ request()->routeIs('ganado.*') ? 'active' : '' }}">
                        <i data-lucide="cow" class="w-5 h-5"></i>
                        <span class="font-semibold">Ganado</span>
                    </a>
                    <a href="{{ route('maquinaria.index') }}" class="sidebar-link {{ request()->routeIs('maquinaria.*') ? 'active' : '' }}">
                        <i data-lucide="tractor" class="w-5 h-5"></i>
                        <span class="font-semibold">Maquinaria</span>
                    </a>
                    <a href="{{ route('clima.index') }}" class="sidebar-link {{ request()->routeIs('clima.*') ? 'active' : '' }}">
                        <i data-lucide="cloud" class="w-5 h-5"></i>
                        <span class="font-semibold">Clima</span>
                    </a>
                    <a href="{{ route('predicciones.index') }}" class="sidebar-link {{ request()->routeIs('predicciones.*') ? 'active' : '' }}">
                        <i data-lucide="brain" class="w-5 h-5"></i>
                        <span class="font-semibold">Predicciones</span>
                    </a>
                    <a href="{{ route('admin.reportes') }}" class="sidebar-link {{ request()->routeIs('admin.reportes') ? 'active' : '' }}">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span class="font-semibold">Reportes</span>
                    </a>
                @else
                    <!-- Producer sidebar -->
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span class="font-semibold">Inicio</span>
                    </a>
                    <a href="{{ route('cultivos.index') }}" class="sidebar-link {{ request()->routeIs('cultivos.*') ? 'active' : '' }}">
                        <i data-lucide="wheat" class="w-5 h-5"></i>
                        <span class="font-semibold">Cultivos</span>
                    </a>
                    <a href="{{ route('ganado.index') }}" class="sidebar-link {{ request()->routeIs('ganado.*') ? 'active' : '' }}">
                        <i data-lucide="cow" class="w-5 h-5"></i>
                        <span class="font-semibold">Ganado</span>
                    </a>
                    <a href="{{ route('actualizaciones.index') }}" class="sidebar-link {{ request()->routeIs('actualizaciones.*') ? 'active' : '' }}">
                        <i data-lucide="file-edit" class="w-5 h-5"></i>
                        <span class="font-semibold">Actualizaciones</span>
                    </a>
                    <a href="{{ route('clima.index') }}" class="sidebar-link {{ request()->routeIs('clima.*') ? 'active' : '' }}">
                        <i data-lucide="cloud" class="w-5 h-5"></i>
                        <span class="font-semibold">Clima</span>
                    </a>
                    <a href="{{ route('cosechas.index') }}" class="sidebar-link {{ request()->routeIs('cosechas.*') ? 'active' : '' }}">
                        <i data-lucide="harvest" class="w-5 h-5"></i>
                        <span class="font-semibold">Cosechas</span>
                    </a>
                    <a href="{{ route('ventas.index') }}" class="sidebar-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                        <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                        <span class="font-semibold">Ventas</span>
                    </a>
                    <a href="{{ route('predicciones.index') }}" class="sidebar-link {{ request()->routeIs('predicciones.*') ? 'active' : '' }}">
                        <i data-lucide="brain" class="w-5 h-5"></i>
                        <span class="font-semibold">Predicciones</span>
                    </a>
                @endif
            </nav>
            
            <div class="p-4 border-t border-agro-accent/20">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full sidebar-link">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span class="font-semibold">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <i data-lucide="menu" class="w-6 h-6 text-agro-dark"></i>
                            </button>
                            <div>
                                <h2 class="text-xl font-semibold text-agro-dark">@yield('page-title', 'Dashboard')</h2>
                                <p class="text-xs text-gray-500 hidden md:block">@yield('page-subtitle', 'Panel de control')</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
                                    <i data-lucide="bell" class="w-5 h-5 text-agro-dark"></i>
                                    @if(isset($alertasActivas) && $alertasActivas > 0)
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                                    @endif
                                </button>
                            </div>
                            
                            <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                                <div class="w-10 h-10 bg-agro-primary rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden md:block">
                                    <p class="text-sm font-semibold text-agro-dark">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->role === 'admin' ? 'Administrador' : 'Productor' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                @if(session('success'))
                <div class="mb-6 alert alert-success">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 alert alert-error">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebar" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden">
        <aside class="w-64 bg-agro-primary text-white h-full transform transition-transform duration-300 -translate-x-full shadow-2xl" id="mobileSidebarContent">
            <div class="p-6 border-b border-agro-accent/20 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-md">
                        <i data-lucide="sprout" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">AgroTrack EC</h1>
                        <p class="text-xs text-white/70">Sistema Agrícola</p>
                    </div>
                </div>
                <button id="closeSidebar" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <nav class="p-4 space-y-1">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.productores') }}" class="sidebar-link {{ request()->routeIs('admin.productores') ? 'active' : '' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span class="font-semibold">Productores</span>
                    </a>
                    <a href="{{ route('admin.cultivos') }}" class="sidebar-link {{ request()->routeIs('admin.cultivos') ? 'active' : '' }}">
                        <i data-lucide="wheat" class="w-5 h-5"></i>
                        <span class="font-semibold">Cultivos</span>
                    </a>
                    <a href="{{ route('ganado.index') }}" class="sidebar-link {{ request()->routeIs('ganado.*') ? 'active' : '' }}">
                        <i data-lucide="cow" class="w-5 h-5"></i>
                        <span class="font-semibold">Ganado</span>
                    </a>
                    <a href="{{ route('maquinaria.index') }}" class="sidebar-link {{ request()->routeIs('maquinaria.*') ? 'active' : '' }}">
                        <i data-lucide="tractor" class="w-5 h-5"></i>
                        <span class="font-semibold">Maquinaria</span>
                    </a>
                    <a href="{{ route('clima.index') }}" class="sidebar-link {{ request()->routeIs('clima.*') ? 'active' : '' }}">
                        <i data-lucide="cloud" class="w-5 h-5"></i>
                        <span class="font-semibold">Clima</span>
                    </a>
                    <a href="{{ route('predicciones.index') }}" class="sidebar-link {{ request()->routeIs('predicciones.*') ? 'active' : '' }}">
                        <i data-lucide="brain" class="w-5 h-5"></i>
                        <span class="font-semibold">Predicciones</span>
                    </a>
                    <a href="{{ route('admin.reportes') }}" class="sidebar-link {{ request()->routeIs('admin.reportes') ? 'active' : '' }}">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span class="font-semibold">Reportes</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span class="font-semibold">Inicio</span>
                    </a>
                    <a href="{{ route('cultivos.index') }}" class="sidebar-link {{ request()->routeIs('cultivos.*') ? 'active' : '' }}">
                        <i data-lucide="wheat" class="w-5 h-5"></i>
                        <span class="font-semibold">Cultivos</span>
                    </a>
                    <a href="{{ route('ganado.index') }}" class="sidebar-link {{ request()->routeIs('ganado.*') ? 'active' : '' }}">
                        <i data-lucide="cow" class="w-5 h-5"></i>
                        <span class="font-semibold">Ganado</span>
                    </a>
                    <a href="{{ route('actualizaciones.index') }}" class="sidebar-link {{ request()->routeIs('actualizaciones.*') ? 'active' : '' }}">
                        <i data-lucide="file-edit" class="w-5 h-5"></i>
                        <span class="font-semibold">Actualizaciones</span>
                    </a>
                    <a href="{{ route('clima.index') }}" class="sidebar-link {{ request()->routeIs('clima.*') ? 'active' : '' }}">
                        <i data-lucide="cloud" class="w-5 h-5"></i>
                        <span class="font-semibold">Clima</span>
                    </a>
                    <a href="{{ route('cosechas.index') }}" class="sidebar-link {{ request()->routeIs('cosechas.*') ? 'active' : '' }}">
                        <i data-lucide="harvest" class="w-5 h-5"></i>
                        <span class="font-semibold">Cosechas</span>
                    </a>
                    <a href="{{ route('ventas.index') }}" class="sidebar-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                        <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                        <span class="font-semibold">Ventas</span>
                    </a>
                    <a href="{{ route('predicciones.index') }}" class="sidebar-link {{ request()->routeIs('predicciones.*') ? 'active' : '' }}">
                        <i data-lucide="brain" class="w-5 h-5"></i>
                        <span class="font-semibold">Predicciones</span>
                    </a>
                @endif
            </nav>
            <div class="p-4 border-t border-agro-accent/20 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full sidebar-link">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span class="font-semibold">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>
    </div>

    @stack('scripts')
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('mobileSidebar');
            const sidebarContent = document.getElementById('mobileSidebarContent');
            sidebar.classList.remove('hidden');
            setTimeout(() => {
                sidebarContent.classList.remove('-translate-x-full');
            }, 10);
            lucide.createIcons();
        });
        
        document.getElementById('closeSidebar')?.addEventListener('click', function() {
            const sidebar = document.getElementById('mobileSidebar');
            const sidebarContent = document.getElementById('mobileSidebarContent');
            sidebarContent.classList.add('-translate-x-full');
            setTimeout(() => {
                sidebar.classList.add('hidden');
            }, 300);
        });
        
        document.getElementById('mobileSidebar')?.addEventListener('click', function(e) {
            if (e.target === this) {
                const sidebar = document.getElementById('mobileSidebar');
                const sidebarContent = document.getElementById('mobileSidebarContent');
                sidebarContent.classList.add('-translate-x-full');
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                }, 300);
            }
        });
    </script>
</body>
</html>
