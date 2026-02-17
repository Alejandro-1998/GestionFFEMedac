<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Listado de Módulos') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Listado de Módulos (Global)</h1>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'crear-modulo')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    Nuevo Módulo
                </button>
            </div>
            <!-- Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($modulosActivos->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-indigo-700 mb-4 border-b border-indigo-200 pb-2">
                        Módulos del Curso Académico Actual
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($modulosActivos as $modulo)
                            <div class="bg-white border-2 border-indigo-100 rounded-xl shadow-sm hover:shadow-md transition-shadow p-5 relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded-bl-lg">
                                    Actual
                                </div>
                                <div class="flex justify-between items-start mb-3 mt-2">
                                    <h4 class="text-lg font-bold text-gray-900">{{ $modulo->nombre }}</h4>
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'eliminar-modulo-{{ $modulo->id }}')" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50" title="Eliminar Módulo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="space-y-3">
                                    @php
                                        // $currentYearId is used in other places? If not needed for the link, I can remove it if it was only for the old link.
                                        // But I need to count students. The count logic: $curso->alumnos->where('curso_academico_id', $currentYearId)->count()
                                        // So I keep $currentYearId logic.
                                        $currentYearId = \App\Models\CursoAcademico::where('actual', true)->value('id');
                                    @endphp
                                    @foreach($modulo->cursos as $curso)
                                        <a href="{{ route('alumnos.curso-actual', $curso->id) }}" 
                                           class="flex items-center justify-between p-3 bg-indigo-50/50 rounded-lg border border-indigo-100 hover:bg-indigo-50 hover:border-indigo-200 hover:shadow-sm transition-all duration-200 group">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm">
                                                    {{ $curso->nombre }}
                                                </span>
                                                <span class="text-gray-600 text-sm font-medium">Alumnos del curso actual: {{ $curso->alumnos->where('curso_academico_id', $currentYearId)->count() }}</span>
                                            </div>
                                            <span class="text-indigo-600 text-sm font-medium hover:underline opacity-0 group-hover:opacity-100 transition-opacity">
                                                Ver Listado &rarr;
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($otrosModulos->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-700 mb-4 border-b border-gray-200 pb-2">
                        Otros Módulos
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($otrosModulos as $modulo)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-lg font-bold text-gray-900">{{ $modulo->nombre }}</h4>
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'eliminar-modulo-{{ $modulo->id }}')" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50" title="Eliminar Módulo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="space-y-3">
                                    @foreach($modulo->cursos as $curso)
                                        <a href="{{ route('alumnos.index', ['search' => '', 'curso_id' => $curso->id]) }}" 
                                           class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-indigo-50 hover:border-indigo-200 hover:shadow-sm transition-all duration-200 group">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm">
                                                    {{ $curso->nombre }}
                                                </span>
                                                <span class="text-gray-600 text-sm font-medium">Alumnos totales: {{ $curso->alumnos->count() }}</span>
                                            </div>
                                            <span class="text-indigo-600 text-sm font-medium hover:underline opacity-0 group-hover:opacity-100 transition-opacity">
                                                Ver &rarr;
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($modulosActivos->isEmpty() && $otrosModulos->isEmpty())
                <div class="col-span-full py-12 text-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-sm font-medium text-gray-900">No hay módulos registrados</h3>
                </div>
            @endif

            @php
                $allModulos = $modulosActivos->merge($otrosModulos);
            @endphp
            @foreach($allModulos as $modulo)
                <!-- Modal Eliminar -->
                <x-modal name="eliminar-modulo-{{ $modulo->id }}" focusable maxWidth="md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            {{ __('¿Eliminar Módulo?') }}
                        </h2>
                        <p class="text-sm text-gray-600 mb-6">
                            ¿Estás seguro de que deseas eliminar el módulo <strong>{{ $modulo->nombre }}</strong>? Esta acción eliminará los cursos asociados. Los alumnos serán desasignados.
                        </p>
                        <div class="flex justify-end">
                            <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                                {{ __('Cancelar') }}
                            </button>
                            <form method="POST" action="{{ route('modulos.destroy', $modulo->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                    {{ __('Eliminar') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </x-modal>
            @endforeach
        </div>
    </div>
</x-app-layout>

    <!-- Modal para crear módulo -->
    <x-modal name="crear-modulo" focusable maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Crear Nuevo Módulo') }}
            </h2>

            <form method="POST" action="{{ route('modulos.store') }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-4">
                    <x-input-label for="nombre" :value="__('Nombre del Módulo')" />
                    <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus placeholder="Ej: Desarrollo de Aplicaciones Web" />
                    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                </div>

                <!-- Duración -->
                <div class="mb-6">
                    <span class="block font-medium text-sm text-gray-700 mb-2">{{ __('Duración / Cursos') }}</span>
                    
                    <div class="flex flex-col gap-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio text-indigo-600 border-gray-300 focus:ring-indigo-500" name="duracion" value="1_anyo">
                            <span class="ml-2 text-gray-700">1 Año (Solo 1º)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio text-indigo-600 border-gray-300 focus:ring-indigo-500" name="duracion" value="2_anyos" checked>
                            <span class="ml-2 text-gray-700">2 Años (1º y 2º)</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('duracion')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
