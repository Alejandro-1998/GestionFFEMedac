@forelse ($empleados as $empleado)
    <tr>
        <td class="px-6 py-4">
            <div class="flex flex-col space-y-1">
                <a href="{{ route('empleados.show', $empleado) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-medium text-sm">
                    {{ $empleado->nombre }} {{ $empleado->apellido }} {{ $empleado->apellido2 }}
                </a>
                <div>
                    <a href="{{ route('empresas.show', $empleado->empresa) }}" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 hover:bg-purple-200">
                        {{ $empleado->empresa->nombre }}
                    </a>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $empleado->cargo }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            {{ $empleado->email ?? '-' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            {{ $empleado->telefono_responsable_laboral }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $empleado->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-empleado-{{ $empleado->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-2">
                Editar
            </button>

            <form action="{{ route('empleados.destroy', $empleado) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este empleado?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">
                    Eliminar
                </button>
            </form>

            <!-- Modal Editar -->
            <x-modal name="editar-empleado-{{ $empleado->id }}" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Editar Empleado') }}
                    </h2>
        
                    <form method="POST" action="{{ route('empleados.update', $empleado) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="empresa_id" value="{{ $empleado->empresa_id }}">
        
                        @include('empleados.partials.form', ['empleado' => $empleado, 'sedes' => $empleado->empresa->sedes, 'modulos' => $modulos, 'suffix' => $empleado->id])
        
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
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            No se encontraron empleados.
        </td>
    </tr>
@endforelse
