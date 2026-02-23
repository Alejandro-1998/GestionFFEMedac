<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Alejandro Caballero Luque">
        <title>Gestión FFE | Davante</title>

        <!-- Fuentes -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=outfit:400,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" href="{{ asset('images/davante.png') }}" type="image/png">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-surface-50 text-surface-800 selection:bg-primary-600 selection:text-white">
        
        <!-- Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary-100/40 rounded-full blur-3xl opacity-60 mix-blend-multiply"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-100/40 rounded-full blur-3xl opacity-60 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Navegación -->
            <nav class="w-full py-6 px-6 lg:px-12 flex justify-between items-center backdrop-blur-md bg-white/70 border-b border-surface-200 sticky top-0 z-50 shadow-sm">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/davante.png') }}" alt="Davante" class="h-12 w-auto">
                    <span class="text-2xl font-display font-bold text-surface-900 tracking-tight">Gestión<span class="text-primary-600">FFE</span></span>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Tablero</a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold shadow-lg shadow-primary-600/20 transition-all hover:scale-105 active:scale-95">Iniciar Sesión</a>
                        @endauth
                    @endif
                </div>
            </nav>

            <!-- Hero -->
            <main class="flex-grow flex items-center justify-center relative px-6 py-12">
                <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    
                    <div class="space-y-8">                        
                        <h1 class="text-5xl lg:text-7xl font-display font-bold text-surface-900 leading-tight">
                            Sistema de Gestión de Alumnos en FFE <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">Davante</span>
                        </h1>
                        
                        <p class="text-lg text-surface-600 leading-relaxed max-w-xl">
                            Gestiona las prácticas de Fase de Formación en Empresa (FFE) de manera eficiente. Controla los convenios entre alumnos y empresas y simplifica el seguimiento académico.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('login') }}" class="px-8 py-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-lg shadow-xl shadow-primary-600/30 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                                Empezar Ahora
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>

                        </div>
                    </div>

                    <!-- Tarjetas Datos -->
                    <div class="grid grid-cols-2 gap-4 lg:gap-6 relative">
                        <!-- Tarjeta 1 -->
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-md border border-surface-200 shadow-lg shadow-surface-200/50 hover:border-primary-200 transition-all group hover:bg-white">
                            <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="text-3xl font-display font-bold text-surface-900 mb-1">{{ $stats['empresas'] }}</div>
                            <div class="text-surface-500 font-medium">Empresas Colaboradoras</div>
                        </div>

                        <!-- Tarjeta 2 -->
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-md border border-surface-200 shadow-lg shadow-surface-200/50 hover:border-primary-200 transition-all group hover:bg-white mt-8">
                            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div class="text-3xl font-display font-bold text-surface-900 mb-1">{{ $stats['alumnos'] }}</div>
                            <div class="text-surface-500 font-medium">Alumnos Activos</div>
                        </div>

                         <!-- Tarjeta 3 -->
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-md border border-surface-200 shadow-lg shadow-surface-200/50 hover:border-primary-200 transition-all group hover:bg-white -mt-8">
                            <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="text-3xl font-display font-bold text-surface-900 mb-1">{{ $stats['cursos'] }}</div>
                            <div class="text-surface-500 font-medium">Cursos Académicos</div>
                        </div>

                        <!-- Tarjeta 4 -->
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-md border border-surface-200 shadow-lg shadow-surface-200/50 hover:border-primary-200 transition-all group hover:bg-white">
                            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="text-3xl font-display font-bold text-surface-900 mb-1">{{ $stats['convenios'] }}</div>
                            <div class="text-surface-500 font-medium">Convenios Firmados</div>
                        </div>

                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-8 border-t border-surface-200 bg-white/50 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-6 text-center text-surface-500 text-sm">
                    &copy; {{ date('Y') }} Gestión FCT. Todos los derechos reservados.
                </div>
            </footer>
        </div>
    </body>
</html>