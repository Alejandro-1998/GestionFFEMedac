@forelse ($alumnos as $alumno)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <a href="{{ route('alumnos.show', $alumno->id) }}" class="flex items-center hover:bg-gray-50 transition duration-150 ease-in-out p-1 rounded-md">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-3">
                    {{ substr($alumno->nombre_completo, 0, 1) }}
                </div>
                <div class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                    {{ $alumno->nombre_completo }}
                </div>
            </a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $alumno->dni }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ $alumno->cursoAcademico ? $alumno->cursoAcademico->anyo : ($alumno->curso ? $alumno->curso->anyo : '-') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
            @if($alumno->empresa)
                {{ $alumno->empresa->nombre }}
            @else
                <span class="text-gray-400 italic">Sin empresa</span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $alumno->created_at->format('d/m/Y') }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-alumno-{{ $alumno->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-2">
                Editar
            </button>

            <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este alumno?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">
                    Eliminar
                </button>
            </form>

            <!-- Modal Editar -->
            <x-modal name="editar-alumno-{{ $alumno->id }}" focusable maxWidth="3xl">
                <div class="p-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Editar Alumno') }}
                    </h2>
        
                    <form method="POST" action="{{ route('alumnos.update', $alumno) }}">
                        @csrf
                        @method('PUT')
        
                        @include('alumnos.partials.form', ['alumno' => $alumno, 'cursos' => $cursos])
        
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
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            No se encontraron alumnos que coincidan con la búsqueda.
        </td>
    </tr>
@endforelse
