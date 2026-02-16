<x-guest-layout>
    <div class="text-center">
        <h1 class="text-9xl font-bold text-primary-600 font-display">419</h1>
        <p class="text-2xl font-bold text-surface-900 mt-4">Página Expirada</p>
        <p class="text-surface-600 mt-2">Tu sesión ha caducado por inactividad. Por favor, recarga la página e intenta de nuevo.</p>

        <div class="mt-8">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors shadow-lg shadow-primary-600/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Ir al Login
            </a>
        </div>
    </div>
</x-guest-layout>
