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
                        'agro-green': '#2E7D32',
                        'agro-green-light': '#81C784',
                        'agro-yellow': '#FBC02D',
                        'agro-brown': '#6D4C41',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-green-100 to-green-200 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-agro-green px-8 py-10 text-white text-center">
                <div class="w-20 h-20 bg-white rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-12 h-12 text-agro-green" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M14.779,12.18 L11.795,8.501 C11.795,8.501 13.396,8.937 13.57,8.937 C14.035,8.937 13.765,8.42 13.57,8.223 L11.185,5.192 C11.185,5.192 12.333,4.918 12.75,4.918 C13.168,4.918 12.947,4.401 12.75,4.204 L9.4,0.061 C9.203,-0.136 8.883,-0.136 8.686,0.061 L5.291,4.161 C5.093,4.358 4.805,4.876 5.291,4.876 C5.777,4.876 6.913,5.192 6.913,5.192 L4.325,8.079 C4.127,8.276 3.768,8.793 4.325,8.793 C4.644,8.793 6.275,8.502 6.275,8.502 L3.317,12.189 C3.12,12.385 2.76,12.903 3.317,12.903 C3.874,12.903 8.008,11.896 8.008,11.896 L8.008,14.941 C8.008,15.478 8.444,15.914 8.983,15.914 C9.52,15.914 9.998,15.478 9.998,14.941 L9.998,11.896 C9.998,11.896 14.373,12.895 14.778,12.895 C15.183,12.895 14.976,12.376 14.779,12.18 L14.779,12.18 Z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">AgroTrack EC</h1>
                <p class="text-green-100 text-sm">Sistema de Gestión Agrícola</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-10">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h2>
                
                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green focus:border-transparent transition"
                            placeholder="ejemplo@correo.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green focus:border-transparent transition"
                            placeholder="••••••••"
                        >
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-agro-green border-gray-300 rounded focus:ring-agro-green">
                            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                        </label>
                        <a href="#" class="text-sm text-agro-green hover:text-green-700 font-medium">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-agro-green text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Iniciar Sesión
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes cuenta? 
                        <a href="#" class="text-agro-green hover:text-green-700 font-semibold">
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

</body>
</html>
