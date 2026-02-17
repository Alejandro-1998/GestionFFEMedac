<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Empresa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sección de Datos de la Empresa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('empresas.index') }}" class="text-blue-600 hover:underline flex items-center">
                            &larr; Volver a Empresas
                        </a>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-700 border-b pb-2">Información de la Empresa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nombre</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $empresa->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $empresa->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">NIF</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $empresa->nif }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Dirección</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $empresa->direccion }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Teléfono</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $empresa->telefono }}</p>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Módulos Asociados</p>
                            @if($modulosAsociados->count() > 0)
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($modulosAsociados as $modulo)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $modulo }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">No tiene módulos asociados.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Lista de Empleados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-700">Empleados Asociados</h3>
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'crear-empleado')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Nuevo Empleado
                        </button>
                    </div>
                    
                    @if($empresa->empleados->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono Responsable</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activo</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($empresa->empleados as $empleado)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <a href="{{ route('empleados.show', $empleado) }}" class="group">
                                                <span class="text-blue-600 group-hover:text-blue-800 group-hover:underline">{{ $empleado->nombre }} {{ $empleado->apellido }} {{ $empleado->apellido2 }}</span>
                                                <span class="text-sm text-gray-700 block group-hover:text-gray-900">{{ $empleado->dni_pasaporte }}</span>
                                            </a>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">{{ $empleado->cargo }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap">{{ $empleado->email ?? '-' }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap">{{ $empleado->telefono_responsable_laboral }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $empleado->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                            <!-- Botón Editar -->
                                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-empleado-{{ $empleado->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                                Editar
                                            </button>
                                            
                                            <!-- Formulario Eliminar -->
                                            <form action="{{ route('empleados.destroy', $empleado) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de querer eliminar este empleado?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Eliminar
                                                </button>
                                            </form>

                                            <!-- Modal Editar (Uno por empleado) -->
                                            <x-modal name="editar-empleado-{{ $empleado->id }}" focusable>
                                                <div class="p-6">
                                                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                                                        {{ __('Editar Empleado') }}
                                                    </h2>
                                        
                                                    <form method="POST" action="{{ route('empleados.update', $empleado) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                                        
                                                        @include('empleados.partials.form', ['empleado' => $empleado, 'sedes' => $empresa->sedes, 'suffix' => $empleado->id])
                                        
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
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay empleados registrados en esta empresa.</p>
                    @endif
                </div>
            </div>

            <!-- Sección de Sedes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-700">Sedes</h3>
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'crear-sede')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Nueva Sede
                        </button>
                    </div>

                    @if($empresa->sedes->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($empresa->sedes as $sede)
                                <div class="border rounded-lg p-4 bg-gray-50 relative">
                                    <h4 class="font-bold text-lg mb-2">{{ $sede->nombre }}</h4>
                                    <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Ubicación:</span> {{ $sede->ubicacion }}</p>
                                    <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Dirección:</span> {{ $sede->direccion }}</p>
                                    <p class="text-sm text-gray-600 mb-4"><span class="font-semibold">Teléfono:</span> {{ $sede->telefono }}</p>
                                    
                                    <div class="flex justify-end space-x-2">
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'editar-sede-{{ $sede->id }}')" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Editar</button>
                                        <form action="{{ route('sedes.destroy', $sede) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta sede?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Eliminar</button>
                                        </form>
                                    </div>

                                    <!-- Modal Editar Sede -->
                                    <x-modal name="editar-sede-{{ $sede->id }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-medium text-gray-900 mb-4">Editar Sede</h2>
                                            <form method="POST" action="{{ route('sedes.update', $sede) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                                                @include('empresas.partials.form-sede', ['sede' => $sede])
                                                <div class="mt-6 flex justify-end">
                                                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded mr-2">Cancelar</button>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay sedes registradas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear sede -->
    <x-modal name="crear-sede" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Añadir Nueva Sede</h2>
            <form method="POST" action="{{ route('sedes.store') }}">
                @csrf
                <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                @include('empresas.partials.form-sede', ['sede' => null])
                <div class="mt-6 flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal para crear empleado -->
    <x-modal name="crear-empleado" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Añadir Nuevo Empleado') }}
            </h2>

            <form method="POST" action="{{ route('empleados.store') }}">
                @csrf
                <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                @include('empleados.partials.form', ['sedes' => $empresa->sedes])

                <div class="mt-6 flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
