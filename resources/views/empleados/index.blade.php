<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Empresas - Empleados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Navegación de Sección Empresas -->
                    <div class="flex flex-wrap gap-2 mb-6 border-b pb-4">
                        <a href="{{ route('empresas.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300">
                            Empresas
                        </a>
                        <a href="{{ route('sedes.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300">
                            Sedes
                        </a>
                        <a href="{{ route('empleados.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition duration-300">
                            Empleados
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3">
                        <h3 class="text-lg font-bold text-gray-800">Listado de Empleados</h3>
                        
                        <!-- Buscador -->
                        <form method="GET" action="{{ route('empleados.index') }}" class="flex w-full sm:w-auto" x-data="{ 
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
                                document.getElementById('empleados-table-body').innerHTML = html;
                            }
                        }">
                            <input type="text" name="search" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Buscar empleado..." class="flex-1 rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md transition duration-300 text-sm font-medium">
                                Buscar
                            </button>
                            @if(request('search'))
                                <a href="{{ route('empleados.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-md transition duration-300 flex items-center text-sm">
                                    Limpiar
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre / Empresa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="empleados-table-body" class="bg-white divide-y divide-gray-200">
                                @include('empleados.partials.table-rows')
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
