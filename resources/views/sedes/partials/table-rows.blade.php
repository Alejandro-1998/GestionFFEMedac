@forelse ($sedes as $sede)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <a href="{{ route('sedes.show', $sede) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                {{ $sede->nombre }}
            </a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <a href="{{ route('empresas.show', $sede->empresa) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 hover:bg-purple-200">
                {{ $sede->empresa->nombre }}
            </a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $sede->ubicacion }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $sede->telefono }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-sede-{{ $sede->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-2">
                Editar
            </button>

            <form action="{{ route('sedes.destroy', $sede) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta sede?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">
                    Eliminar
                </button>
            </form>

            <!-- Modal Editar -->
            <x-modal name="editar-sede-{{ $sede->id }}" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Editar Sede</h2>
                    <form method="POST" action="{{ route('sedes.update', $sede) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="empresa_id" value="{{ $sede->empresa_id }}">
                        @include('empresas.partials.form-sede', ['sede' => $sede])
                        <div class="mt-6 flex justify-end">
                            <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded mr-2">Cancelar</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                        </div>
                    </form>
                </div>
            </x-modal>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
            No se encontraron sedes.
        </td>
    </tr>
@endforelse
