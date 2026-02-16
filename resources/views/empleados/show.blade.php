<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ficha del Empleado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline flex items-center">
                            &larr; Volver a Empleados
                        </a>
                    </div>

                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <h3 class="text-2xl font-bold text-gray-800">
                            {{ $empleado->nombre }} {{ $empleado->apellido }} {{ $empleado->apellido2 }}
                        </h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('empresas.show', $empleado->empresa_id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300">
                                 Ver Empresa
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Información Personal -->
                        <div>
                            <h4 class="text-lg font-semibold text-indigo-600 mb-4 border-b border-indigo-100 pb-2">Información Personal</h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">DNI / Pasaporte</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $empleado->dni_pasaporte }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha de Nacimiento</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $empleado->fecha_nacimiento }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $empleado->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información Laboral -->
                        <div>
                            <h4 class="text-lg font-semibold text-indigo-600 mb-4 border-b border-indigo-100 pb-2">Información Laboral</h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Cargo</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $empleado->cargo }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Sede</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $empleado->sede->nombre ?? 'Sin asignar' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Responsable Laboral (Teléfono)</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $empleado->telefono_responsable_laboral }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Estado</p>
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $empleado->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos de la Empresa Asociada -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4">Empresa Asociada</h4>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-gray-600">Empresa: <a href="{{ route('empresas.show', $empleado->empresa_id) }}" class="text-blue-600 hover:underline font-bold">{{ $empleado->empresa->nombre ?? 'N/A' }}</a></p>
                            <p class="text-gray-600">CIF: <span class="font-medium">{{ $empleado->empresa->cif ?? 'N/A' }}</span></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
