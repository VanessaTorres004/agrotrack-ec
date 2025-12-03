<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AgroTrack - Sistema de Gestión Agrícola')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        accent: {
                            500: '#d97706',
                            600: '#b45309',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js for IDC visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-md border-b-2 border-primary-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-3">
                    <div class="bg-primary-600 rounded-lg p-2">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">AgroTrack</h1>
                        <p class="text-xs text-gray-500">Sistema de Gestión Agrícola</p>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 transition {{ request()->routeIs('dashboard') ? 'text-primary-600 font-semibold' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('cultivos.index') }}" 
                       class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 transition {{ request()->routeIs('cultivos.*') ? 'text-primary-600 font-semibold' : '' }}">
                        <i class="fas fa-leaf"></i>
                        <span>Cultivos</span>
                    </a>
                    <a href="{{ route('actualizaciones.create') }}" 
                       class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 transition {{ request()->routeIs('actualizaciones.*') ? 'text-primary-600 font-semibold' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Actividades</span>
                    </a>
                    <a href="{{ route('clima.create') }}" 
                       class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 transition {{ request()->routeIs('clima.*') ? 'text-primary-600 font-semibold' : '' }}">
                        <i class="fas fa-cloud-sun"></i>
                        <span>Clima</span>
                    </a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-600 hover:text-primary-600 transition">
                        <i class="fas fa-bell text-lg"></i>
                        @if(isset($alertasCount) && $alertasCount > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $alertasCount }}
                        </span>
                        @endif
                    </button>
                    <div class="flex items-center space-x-2 border-l pl-4">
                        <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->nombre ?? 'P', 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user()->nombre ?? 'Productor' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} AgroTrack. Sistema de Gestión Agrícola.
            </p>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>

