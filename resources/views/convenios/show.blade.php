<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Convenio') }}
        </h2>
    </x-slot>

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
                             <a href="{{ route('convenios.edit', $convenio->id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-200 active:bg-yellow-600 disabled:opacity-25 transition">
                                Editar
                            </a>
                            <form action="{{ route('convenios.destroy', $convenio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este convenio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 active:bg-red-700 disabled:opacity-25 transition">
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
</x-app-layout>
