<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instituto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    


                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Cursos Académicos</h3>
                        
                        <!-- Buscador -->
                        <form method="GET" action="{{ route('cursos.index') }}" class="flex" x-data="{ 
                            search: '{{ request('search') }}',
                            async performSearch() {
                                let url = new URL(window.location.protocol + '//' + window.location.host + window.location.pathname);
                                url.searchParams.set('search', this.search);
                                
                                // Push state to update URL without reload
                                window.history.pushState({}, '', url);

                                const response = await fetch(url, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                });
                                const html = await response.text();
                                document.getElementById('cursos-grid').innerHTML = html;
                            }
                        }">
                            <input type="text" name="search" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Buscar año..." class="rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md transition duration-300 text-sm font-medium">
                                Buscar
                            </button>
                        </form>

                        <!-- Botón para abrir modal de creación -->
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'crear-curso')" class="ml-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Nuevo Curso
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div id="cursos-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @include('cursos.partials.cards')
                    </div>

                    <div class="mt-4">
                        {{ $cursos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear curso -->
    <x-modal name="crear-curso" focusable maxWidth="3xl">
        <div class="p-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Nuevo Curso Académico') }}
            </h2>

            <form method="POST" action="{{ route('cursos.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="anyo" class="block text-sm font-medium text-gray-700">Año Académico</label>
                    <input type="text" name="anyo" placeholder="Ej: 2024-2025" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>



                <div class="mt-8 flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2 mb-6">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 mr-8 mb-6">
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
