@forelse ($cursos as $curso)
    <div class="bg-white group relative overflow-hidden rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 transform hover:-translate-y-1">
        <!-- Enlace principal que cubre toda la tarjeta -->
        <a href="{{ route('cursos.show', $curso->id) }}" class="absolute inset-0 z-10">
            <span class="sr-only">Ver curso {{ $curso->anyo }}</span>
        </a>

        <!-- Decoración de fondo -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl opacity-50 transition-opacity group-hover:opacity-100"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-blue-50 rounded-full blur-2xl opacity-50 transition-opacity group-hover:opacity-100"></div>
        
        <div class="p-6 relative z-10 pointer-events-none">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                @if(Auth::user()->rol === 'admin')
                    <!-- Acciones Admin (z-20 y pointer-events-auto para que sean clickeables sobre el enlace principal) -->
                    <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 relative z-20 pointer-events-auto">
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-curso-{{ $curso->id }}')" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors rounded-full hover:bg-indigo-50" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este curso?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors rounded-full hover:bg-red-50" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <h4 class="text-2xl font-bold text-gray-800 mb-2 group-hover:text-indigo-700 transition-colors">
                {{ $curso->anyo }}
            </h4>
            <p class="text-sm text-gray-500 font-medium">Curso Académico</p>
            
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-sm">
                <span class="text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                    </svg>
                    Ver detalles
                </span>
                <div class="h-8 w-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>

            @if(Auth::user()->rol === 'admin')
                <!-- Modal Editar (Se mueve fuera del contenedor pointer-events-none del contenido principal o se maneja con cuidado) -->
                <!-- Al estar aquí dentro, necesitamos que el modal en sí sea interactivo. 
                        x-modal suele usar portales o fixed positioning, así que debería estar bien. 
                        El botón para abrirlo ya tiene pointer-events-auto. -->
                <x-modal name="editar-curso-{{ $curso->id }}" focusable maxWidth="3xl">
                    <div class="p-8">
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
            
                            <div class="mt-8 flex justify-end">
                                <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2 mb-6">
                                    {{ __('Cancelar') }}
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 mr-8 mb-6">
                                    {{ __('Actualizar') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            @endif
        </div>
    </div>
@empty
    <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <span class="text-lg font-medium">No hay cursos académicos registrados</span>
        <p class="text-sm mt-1">Utiliza el botón "Nuevo Curso" para añadir uno.</p>
    </div>
@endforelse
