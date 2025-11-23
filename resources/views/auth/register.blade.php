<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AgroTrack EC</title>

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
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>

<body class="bg-agro-sand min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

            <!-- Header -->
            <div class="bg-agro-primary px-8 py-10 text-white text-center">
                <div class="w-20 h-20 bg-white/20 rounded-xl mx-auto mb-4 flex items-center justify-center">
                    <i data-lucide="sprout" class="w-12 h-12"></i>
                </div>
                <h1 class="text-3xl font-bold">Crear Cuenta</h1>
                <p class="text-white/90 text-sm mt-1">Sistema de Gestión Agrícola</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-10">

                @if($errors->any())
                    <div class="mb-4 text-red-600 font-semibold bg-red-100 border border-red-300 p-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold mb-2">Nombre Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agro-primary"
                               placeholder="Tu nombre">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agro-primary"
                               placeholder="ejemplo@correo.com">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg"
                               placeholder="0991234567">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Cédula</label>
                        <input type="text" name="cedula" value="{{ old('cedula') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg"
                               placeholder="1712345678">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Rol</label>
                        <select name="role" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            <option value="productor" {{ old('role') == 'productor' ? 'selected' : '' }}>Productor</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Contraseña</label>
                        <input type="password" name="password"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <button class="w-full bg-agro-primary text-white py-3 rounded-lg font-semibold hover:bg-opacity-90 transition">
                        Crear Cuenta
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm">¿Ya tienes cuenta?
                        <a href="{{ route('login') }}" class="text-agro-primary font-semibold">Inicia sesión</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script> lucide.createIcons(); </script>
</body>
</html>
