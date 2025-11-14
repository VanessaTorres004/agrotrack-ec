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
                        'agro-green': '#2E7D32',
                        'agro-green-light': '#81C784',
                        'agro-yellow': '#FBC02D',
                        'agro-yellow-light': '#FFF59D',
                        'agro-bg': '#E8F5E9',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-agro-bg min-h-screen">
    
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-agro-green rounded-lg flex items-center justify-center text-white font-bold text-xl">
                        A
                    </div>
                    <h1 class="text-2xl font-bold text-agro-green">AgroTrack EC</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <input type="search" placeholder="Buscar..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green">
                    
                    <div class="relative">
                        <button class="w-10 h-10 bg-agro-yellow rounded-full flex items-center justify-center text-white font-semibold hover:bg-agro-yellow-light transition">
                            🔔
                        </button>
                        @if(isset($alertasActivas) && $alertasActivas > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $alertasActivas }}
                        </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-agro-green-light rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-sm">
                            <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-gray-500 text-xs">{{ auth()->user()->role === 'admin' ? 'Administrador' : 'Productor' }}</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Salir</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg min-h-screen">
            <nav class="p-4">
                @if(auth()->user()->isAdmin())
                    <!-- Admin sidebar with all modules including ganado, vacunas, predicciones -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition {{ request()->routeIs('dashboard') ? 'bg-agro-green text-white' : 'text-gray-700' }}">
                        <span>📊</span>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.productores') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>👥</span>
                        <span class="font-medium">Productores</span>
                    </a>
                    <a href="{{ route('admin.cultivos') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>🌾</span>
                        <span class="font-medium">Cultivos</span>
                    </a>
                    <a href="{{ route('ganado.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>🐄</span>
                        <span class="font-medium">Ganado</span>
                    </a>
                    <a href="{{ route('clima.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>☁️</span>
                        <span class="font-medium">Clima</span>
                    </a>
                    <a href="{{ route('predicciones.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>🔮</span>
                        <span class="font-medium">Predicciones</span>
                    </a>
                    <a href="{{ route('admin.reportes') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>📈</span>
                        <span class="font-medium">Reportes</span>
                    </a>
                @else
                    <!-- Producer sidebar with ganado and predicciones added -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition {{ request()->routeIs('dashboard') ? 'bg-agro-green text-white' : 'text-gray-700' }}">
                        <span>📊</span>
                        <span class="font-medium">Inicio</span>
                    </a>
                    <a href="{{ route('cultivos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition {{ request()->routeIs('cultivos.*') ? 'bg-agro-green text-white' : 'text-gray-700' }}">
                        <span>🌾</span>
                        <span class="font-medium">Cultivos</span>
                    </a>
                    <a href="{{ route('ganado.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition {{ request()->routeIs('ganado.*') ? 'bg-agro-green text-white' : 'text-gray-700' }}">
                        <span>🐄</span>
                        <span class="font-medium">Ganado</span>
                    </a>
                    <a href="{{ route('actualizaciones.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>📝</span>
                        <span class="font-medium">Actualizaciones</span>
                    </a>
                    <a href="{{ route('clima.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>☁️</span>
                        <span class="font-medium">Clima</span>
                    </a>
                    <a href="{{ route('cosechas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>🚜</span>
                        <span class="font-medium">Cosechas</span>
                    </a>
                    <a href="{{ route('ventas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition text-gray-700">
                        <span>💰</span>
                        <span class="font-medium">Ventas</span>
                    </a>
                    <a href="{{ route('predicciones.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-agro-bg transition {{ request()->routeIs('predicciones.*') ? 'bg-agro-green text-white' : 'text-gray-700' }}">
                        <span>🔮</span>
                        <span class="font-medium">Predicciones</span>
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
