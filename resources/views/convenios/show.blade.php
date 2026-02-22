<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Convenio') }}
        </h2>
    </x-slot>

    <div x-data="{ confirmCancel: false }">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('convenios.index') }}" class="text-blue-600 hover:underline flex items-center">
                            &larr; Volver a Convenios
                        </a>
                    </div>

                    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-6 flex justify-between items-center">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-indigo-700">
                                    Convenio Activo
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                             <a href="{{ route('convenios.edit', $convenio->id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                                Editar
                            </a>
                            @if($convenio->estado === 'cancelada')
                                <span class="inline-flex items-center px-3 py-1 bg-gray-200 rounded-md text-xs font-semibold text-gray-500 uppercase">Cancelado</span>
                            @else
                                <button type="button" @click="confirmCancel = true" class="inline-flex items-center px-3 py-1 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 transition">
                                    Cancelar Convenio
                                </button>
                            @endif
                            <form action="{{ route('convenios.destroy', $convenio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este convenio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Información del Alumno</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p><span class="font-semibold">Nombre:</span> {{ $convenio->alumno->nombre_completo }}</p>
                                <p><span class="font-semibold">Email:</span> {{ $convenio->alumno->email }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Información de la Empresa</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p><span class="font-semibold">Empresa:</span> {{ $convenio->empresa->nombre }}</p>
                                <p><span class="font-semibold">Sede:</span> {{ $convenio->sede->nombre ?? 'N/A' }}</p>
                                <p><span class="font-semibold">Tutor Laboral:</span> {{ $convenio->tutorLaboral->nombre ?? 'Sin asignar' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detalles del Convenio</h3>
                        <div class="bg-gray-50 p-6 rounded-lg grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Curso Académico</h4>
                                <p class="mt-1 text-gray-900 font-bold">{{ $convenio->curso->anyo ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Profesor Tutor</h4>
                                <p class="mt-1 text-gray-900">{{ $convenio->profesor->nombre ?? 'Sin asignar' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Horas</h4>
                                <p class="mt-1 text-gray-900">{{ $convenio->total_horas }} hrs</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Fecha Inicio</h4>
                                <p class="mt-1 text-gray-900">{{ $convenio->fecha_inicio }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Fecha Fin</h4>
                                <p class="mt-1 text-gray-900">{{ $convenio->fecha_fin }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal confirmación cancelar -->
    <div x-show="confirmCancel" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="confirmCancel" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div x-show="confirmCancel" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-lg shadow-xl p-6 max-w-md w-full z-10">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cancelar Convenio</h3>
                        <p class="mt-2 text-sm text-gray-600">¿Estás seguro? El convenio quedará marcado como <strong>cancelado</strong> y no podrá volver a estado activo automáticamente.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="confirmCancel = false" class="px-4 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Volver
                    </button>
                    <form action="{{ route('convenios.cancelar', $convenio->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded-md bg-orange-500 text-sm font-medium text-white hover:bg-orange-600 transition">
                            Sí, cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
</x-app-layout>
