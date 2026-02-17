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
                        <h3 class="text-lg font-bold">Listado de Alumnos</h3>
                        
                        <!-- Buscador -->
                        <form method="GET" action="{{ route('alumnos.index') }}" class="flex" x-data="{ 
                            search: '{{ request('search') }}',
                            async performSearch() {
                                let url = new URL(window.location.href);
                                url.searchParams.set('search', this.search);
                                
                                // Push state to update URL without reload
                                window.history.pushState({}, '', url);

                                const response = await fetch(url, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                });
                                const html = await response.text();
                                document.getElementById('alumnos-table-body').innerHTML = html;
                            }
                        }">
                            <select name="curso_academico_id" class="rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm mr-2" onchange="this.form.submit()">
                                <option value="">Todos los Cursos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_academico_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->anyo }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="curso_id" class="rounded-l-none border-l-0 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm mr-2 w-64" onchange="this.form.submit()">
                                <option value="">Todos los Módulos</option>
                                @foreach($cursosDisponibles as $c)
                                    <option value="{{ $c->id }}" {{ request('curso_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->modulo->nombre }} - {{ $c->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="search" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Buscar por nombre o DNI..." class="w-64 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md transition duration-300">
                                Buscar
                            </button>
                        </form>

                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'crear-alumno')" class="ml-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Nuevo Alumno
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="alumnos-table-body" class="bg-white divide-y divide-gray-200">
                                @include('alumnos.partials.table-rows')
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $alumnos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear alumno -->
    <x-modal name="crear-alumno" focusable maxWidth="3xl">
        <div class="p-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Añadir Nuevo Alumno') }}
            </h2>

            <form method="POST" action="{{ route('alumnos.store') }}">
                @csrf

                @include('alumnos.partials.form', ['alumno' => null, 'cursos' => $cursos])

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
