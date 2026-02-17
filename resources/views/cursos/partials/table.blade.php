@foreach ($cursos as $curso)
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
            <a href="{{ route('cursos.show', $curso->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold text-lg">
                {{ $curso->anyo }}
            </a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            @if(Auth::user()->rol === 'admin')
                <div class="flex items-center space-x-3">
                    <!-- Botón Editar con Modal -->
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-curso-{{ $curso->id }}')" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </button>

                    <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este curso?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </form>

                    <!-- Modal Editar (Uno por curso) -->
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
                </div>
            @else
                <span class="text-gray-400 text-xs">Solo lectura</span>
            @endif
        </td>
    </tr>
@endforeach

@if ($cursos->isEmpty())
    <tr>
        <td colspan="2" class="px-6 py-12 text-center text-gray-500">
            <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="text-lg font-medium">No hay cursos académicos registrados</span>
                <p class="text-sm mt-1">Utiliza el botón "Nuevo Curso" para añadir uno.</p>
            </div>
        </td>
    </tr>
@endif
