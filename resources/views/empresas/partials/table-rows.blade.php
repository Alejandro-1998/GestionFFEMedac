@foreach ($empresas as $empresa)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('empresas.show', $empresa) }}" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $empresa->nombre }}</a></td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $empresa->direccion }}</td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $empresa->telefono }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <!-- Botón Editar con Modal -->
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-empresa-{{ $empresa->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-2">
                Editar
            </button>

            <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">
                    Eliminar
                </button>
            </form>

            <!-- Modal Editar (Uno por empresa) -->
            <x-modal name="editar-empresa-{{ $empresa->id }}" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Editar Empresa') }}
                    </h2>
        
                    <form method="POST" action="{{ route('empresas.update', $empresa) }}">
                        @csrf
                        @method('PUT')
        
                        @include('empresas.partials.form', ['empresa' => $empresa])
        
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
@endforeach
