<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ficha del Alumno') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('alumnos.index') }}" class="text-blue-600 hover:underline flex items-center">
                            &larr; Volver a Alumnos
                        </a>
                    </div>

                    <div class="flex items-center mb-6">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-2xl mr-4">
                            {{ substr($alumno->nombre_completo, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $alumno->nombre_completo }}</h3>
                            <p class="text-gray-500">DNI: {{ $alumno->dni_encriptado }} (Original: {{ $alumno->dni }})</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Información Académica</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Curso Académico</h4>
                                <p class="mt-1 text-gray-900 font-medium">{{ $alumno->curso ? $alumno->curso->anyo : 'Sin asignar' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Empresa Asignada</h4>
                                <p class="mt-1 text-gray-900">
                                    @if($alumno->empresa)
                                        <a href="{{ route('empresas.show', $alumno->empresa->id) }}" class="text-blue-600 hover:underline">
                                            {{ $alumno->empresa->nombre }}
                                        </a>
                                    @else
                                        <span class="italic text-gray-500">Sin asignar</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Nota Media</h4>
                                <p class="mt-1 text-gray-900 font-medium">{{ $alumno->nota_media ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Calificaciones</h4>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @for($i = 1; $i <= 8; $i++)
                                <li class="flex items-center justify-between border-b border-gray-100 pb-2">
                                    <span class="text-gray-600 font-medium">Nota {{ $i }}</span>
                                    <span class="font-bold text-gray-900">{{ $alumno->{'nota_'.$i} ?? '-' }}</span>
                                </li>
                            @endfor
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Información del Registro</h4>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
