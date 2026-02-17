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
                            <p class="text-gray-500">DNI: {{ $alumno->dni }}</p>
                            <p class="text-gray-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $alumno->email }}" class="hover:underline">{{ $alumno->email }}</a>
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Información Académica</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Curso Académico</h4>
                                <p class="mt-1 text-gray-900 font-medium">{{ $alumno->cursoAcademico ? $alumno->cursoAcademico->anyo : 'Sin asignar' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Módulo</h4>
                                <p class="mt-1 text-gray-900 font-medium">{{ $alumno->curso && $alumno->curso->modulo ? $alumno->curso->modulo->nombre : 'Sin asignar' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Curso</h4>
                                <p class="mt-1 text-gray-900 font-medium">{{ $alumno->curso ? $alumno->curso->nombre : 'Sin asignar' }}</p>
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
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4 border-b pb-2">
                            <h4 class="text-lg font-semibold text-gray-700">Calificaciones</h4>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500 uppercase tracking-wider mr-2">Nota Media:</span>
                                <span class="text-xl font-bold {{ $alumno->nota_media >= 5 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $alumno->nota_media ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex justify-start gap-4 overflow-x-auto py-2">
                            @for($i = 1; $i <= 8; $i++)
                                <div class="flex flex-col items-center min-w-[3rem]">
                                    <span class="text-xs text-gray-400 uppercase mb-1">N{{ $i }}</span>
                                    <span class="text-lg font-bold text-green-600">
                                        {{ $alumno->{'nota_'.$i} ?? '-' }}
                                    </span>
                                </div>
                            @endfor
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>
