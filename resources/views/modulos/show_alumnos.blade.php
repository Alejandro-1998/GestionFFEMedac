<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Alumnos del Módulo: ') }} {{ $modulo->nombre }} <span class="text-sm font-normal text-gray-500">({{ $cursoActual->anyo }})</span>
            </h2>
            <a href="{{ route('modulos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition duration-300">
                {{ __('Volver a Módulos') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    @if($alumnos->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">No hay alumnos asignados a este módulos en el curso académico actual.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Nombre Completo') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Nota Media') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Notas (1-8)') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Empresa') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('DNI') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($alumnos as $alumno)
                                    <tr>
                                        <!-- Nombre -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $alumno->nombre_completo }}</div>
                                            <div class="text-xs text-gray-500">{{ $alumno->curso->nombre }}</div>
                                        </td>
                                        
                                        <!-- Nota Media -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($alumno->nota_media !== null)
                                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $alumno->nota_media >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $alumno->nota_media }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>

                                        <!-- Notas 1-8 -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center gap-1">
                                                @foreach(range(1, 8) as $i)
                                                    @php $nota = $alumno->{'nota_' . $i}; @endphp
                                                    <span class="inline-block w-6 text-center text-xs {{ $nota !== null ? ($nota >= 5 ? 'text-green-600' : 'text-red-600') : 'text-gray-300' }}" title="Nota {{ $i }}">
                                                        {{ $nota ?? '-' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>

                                        <!-- Empresa -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($alumno->empresa)
                                                <div class="text-sm text-gray-900">{{ $alumno->empresa->nombre }}</div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ __('Sin asignar') }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- DNI Censurado -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $alumno->dni_encriptado ?? $alumno->dni }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
