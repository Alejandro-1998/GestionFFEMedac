<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fuentes -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=outfit:400,600,700,800&display=swap" rel="stylesheet" />
        
        <link rel="icon" href="{{ asset('images/davante.png') }}" type="image/png">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-surface-800 antialiased bg-surface-50 selection:bg-primary-600 selection:text-white">
        
        <!-- Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
             <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary-100/30 rounded-full blur-3xl opacity-50 mix-blend-multiply"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-100/30 rounded-full blur-3xl opacity-50 mix-blend-multiply"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-6">
            <div class="mb-8">
                <a href="/public" class="flex flex-col items-center gap-2 group">
                    <img src="{{ asset('images/davante.png') }}" alt="Davante" class="h-20 w-auto group-hover:scale-110 transition-transform">
                    <span class="text-2xl font-display font-bold text-surface-900 tracking-tight">Gestión<span class="text-primary-600">FFE</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white border border-surface-200 shadow-xl shadow-surface-200/50 sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
