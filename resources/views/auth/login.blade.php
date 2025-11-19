<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AgroTrack EC</title>
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
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { 
            font-family: 'Inter', 'Nunito', 'Poppins', sans-serif; 
        }
    </style>
</head>
<body class="bg-agro-sand min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            
            <!-- Header -->
            <div class="bg-agro-primary px-8 py-10 text-white text-center shadow-lg">
                <div class="w-20 h-20 bg-white/20 rounded-2xl mx-auto mb-4 flex items-center justify-center backdrop-blur-sm shadow-md">
                    <i data-lucide="sprout" class="w-12 h-12 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">AgroTrack EC</h1>
                <p class="text-white/90 text-sm font-semibold">Sistema de Gestión Agrícola</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-10">
                <h2 class="text-2xl font-semibold text-agro-dark mb-6 text-center">Iniciar Sesión</h2>
                
                @if($errors->any())
                <div class="alert alert-error mb-6">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <p class="text-sm font-semibold">{{ $errors->first() }}</p>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-agro-dark mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-primary focus:border-transparent transition-all duration-200"
                                placeholder="ejemplo@correo.com"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-agro-dark mb-2">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-primary focus:border-transparent transition-all duration-200"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-agro-primary border-gray-300 rounded focus:ring-agro-primary focus:ring-2">
                            <span class="ml-2 text-sm text-agro-dark">Recordarme</span>
                        </label>
                        <a href="#" class="text-sm text-agro-primary hover:text-agro-accent font-medium transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-agro-primary text-white py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-agro-primary focus:ring-offset-2"
                    >
                        Iniciar Sesión
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes cuenta? 
                        <a href="#" class="text-agro-primary hover:text-agro-accent font-semibold transition-colors">
                            Regístrate aquí
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center">
                    © 2025 AgroTrack EC. Sistema para productores ecuatorianos.
                </p>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
