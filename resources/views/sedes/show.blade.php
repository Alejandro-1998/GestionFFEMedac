<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Sede') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('sedes.index') }}" class="text-blue-600 hover:underline flex items-center">
                            &larr; Volver a Sedes
                        </a>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $sede->nombre }}</h3>
                                <p class="text-gray-500">Empresa: <a href="{{ route('empresas.show', $sede->empresa) }}" class="text-indigo-600 hover:underline">{{ $sede->empresa->nombre }}</a></p>
                            </div>
                            <div class="flex space-x-2">
                                <!-- Botones de acción si fueran necesarios -->
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Ubicación</h4>
                                <p class="mt-1 text-gray-900">{{ $sede->ubicacion }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Dirección</h4>
                                <p class="mt-1 text-gray-900">{{ $sede->direccion }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Teléfono</h4>
                                <p class="mt-1 text-gray-900">{{ $sede->telefono }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Empleados Asociados -->
                    <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Empleados en esta Sede</h3>
                            
                            @if($sede->empleados->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($sede->empleados as $empleado)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="{{ route('empleados.show', $empleado) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                        {{ $empleado->nombre }} {{ $empleado->apellido }} {{ $empleado->apellido2 }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $empleado->cargo }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $empleado->email ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $empleado->telefono_responsable_laboral }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $empleado->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('empleados.show', $empleado) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Ver</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-gray-500 text-center py-4">No hay empleados asignados a esta sede.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
