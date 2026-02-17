<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Curso Académico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('cursos.index') }}" class="text-blue-600 hover:underline flex items-center">
                            &larr; Volver a Cursos
                        </a>
                    </div>

                    <div class="flex justify-between items-start mb-6 border-b pb-6">
                        <div class="flex items-center gap-4">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $curso->anyo }}</h3>
                            
                            @if(Auth::user()->rol === 'admin')
                                <div class="flex items-center gap-2">
                                    <!-- Edit Button -->
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-curso')" class="text-gray-400 hover:text-indigo-600 transition-colors" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'eliminar-curso')" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Edit Modal -->
                                <x-modal name="editar-curso" focusable maxWidth="md">
                                    <div class="p-6">
                                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                                            {{ __('Editar Curso Académico') }}
                                        </h2>
                                        <form method="POST" action="{{ route('cursos.update', $curso->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-4">
                                                <label for="anyo" class="block text-sm font-medium text-gray-700">Año Académico</label>
                                                <input type="text" name="anyo" value="{{ old('anyo', $curso->anyo) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                                                    {{ __('Cancelar') }}
                                                </button>
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                                    {{ __('Actualizar') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>

                                <!-- Delete Modal -->
                                <x-modal name="eliminar-curso" focusable maxWidth="md">
                                    <div class="p-6">
                                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                                            {{ __('Eliminar Curso Académico') }}
                                        </h2>
                                        <p class="text-sm text-gray-600 mb-6">
                                            ¿Estás seguro de que deseas eliminar el curso <strong>{{ $curso->anyo }}</strong>? Esta acción eliminará todos los módulos y alumnos asociados y no se puede deshacer.
                                        </p>
                                        <div class="flex justify-end">
                                            <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                                                {{ __('Cancelar') }}
                                            </button>
                                            <form method="POST" action="{{ route('cursos.destroy', $curso->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                                    {{ __('Eliminar') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </x-modal>
                            @endif
                        </div>
                    </div>


                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mt-6 mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-6 mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
                        </div>
                    @endif

                <!-- Modules Section -->
                <div class="mt-8" x-data="{ moduloToDeleteId: null, moduloToDeleteName: '', deleteUrlTemplate: '{{ route('modulos.destroy', 'PLACEHOLDER') }}' }">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Módulos Asociados
                        </h3>
                        
                        <button type="button" x-on:click="$dispatch('open-modal', 'gestionar-modulos')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Gestionar Módulos
                        </button>
                    </div>

                    <!-- Manage Modules Modal -->
                    <x-modal name="gestionar-modulos" focusable maxWidth="lg">
                        <form method="POST" action="{{ route('cursos.syncModulos', $curso->id) }}" class="p-6">
                            @csrf
                            <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                                Gestionar Módulos del Curso {{ $curso->anyo }}
                            </h2>
                            <p class="text-sm text-gray-600 mb-4">Selecciona los módulos que formarán parte de este curso académico.</p>
                            
                            <div class="mt-4 max-h-96 overflow-y-auto space-y-2 border rounded-md p-4 bg-gray-50">
                                @forelse($todosLosModulos as $modulo)
                                    <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 shadow-sm cursor-pointer hover:bg-indigo-50 transition-colors">
                                        <input type="checkbox" name="modulos[]" value="{{ $modulo->id }}" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-5 w-5"
                                               @if($curso->modulos->contains($modulo->id)) checked @endif>
                                        <span class="ml-3 text-sm font-medium text-gray-700 select-none flex-1">
                                            {{ $modulo->nombre }}
                                        </span>
                                    </label>
                                @empty
                                    <div class="text-center py-4 text-gray-500">
                                        No hay módulos disponibles en el sistema. <a href="{{ route('modulos.index') }}" class="text-indigo-600 hover:underline">Crear uno</a>.
                                    </div>
                                @endforelse
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" x-on:click="$dispatch('close')" class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium py-2 px-4 rounded shadow-sm transition duration-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow transition duration-300">
                                    Guardar Selección
                                </button>
                            </div>
                        </form>
                    </x-modal>

                        <!-- Modules List -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($curso->modulos as $modulo)
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="text-lg font-bold text-gray-900">{{ $modulo->nombre }}</h4>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        @foreach($modulo->cursos as $subCurso)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-indigo-50 hover:border-indigo-100 transition-colors group">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm">
                                                        {{ $subCurso->nombre }}
                                                    </span>
                                                    <span class="text-gray-600 text-sm font-medium">Alumnos: {{ $subCurso->alumnos->where('curso_academico_id', $curso->id)->count() }}</span>
                                                </div>
                                                <a href="{{ route('alumnos.index', ['curso_academico_id' => $curso->id, 'curso_id' => $subCurso->id]) }}" 
                                                   class="text-indigo-600 text-sm font-medium hover:underline opacity-0 group-hover:opacity-100 transition-opacity">
                                                    Ver Alumnos &rarr;
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-12 text-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <h3 class="text-sm font-medium text-gray-900">No hay módulos creados</h3>
                                    <p class="mt-1 text-sm text-gray-500">Utiliza el formulario de arriba para añadir una titulación.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Delete Module Modal -->
                        <x-modal name="eliminar-modulo" focusable maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Eliminar Módulo
                                </h2>
                                <p class="text-sm text-gray-600 mb-6">
                                    ¿Estás seguro de que deseas eliminar el módulo <strong x-text="moduloToDeleteName"></strong>? <br>
                                    Esta acción eliminará todos los cursos y alumnos asociados a este módulo.
                                </p>
                                <div class="flex justify-end">
                                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                                        Cancelar
                                    </button>
                                    <form method="POST" :action="deleteUrlTemplate.replace('PLACEHOLDER', moduloToDeleteId)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </x-modal>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
