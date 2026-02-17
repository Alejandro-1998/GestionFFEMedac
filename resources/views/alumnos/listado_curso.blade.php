<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Alumnos - {{ $curso->nombre }} ({{ $curso->modulo->nombre }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 flex justify-between items-center">
                        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Volver atrás
                        </a>
                        <span class="text-gray-500 text-sm">Curso Académico: {{ $cursoAcademico->anyo }}</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre Completo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nota Media
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Notas (1-8)
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Empresa
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        DNI
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($alumnos as $alumno)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('alumnos.show', $alumno->id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                                    {{ $alumno->nombre_completo }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $alumno->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($alumno->nota_media !== null)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $alumno->nota_media >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ number_format($alumno->nota_media, 2) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex gap-4">
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_1 ?? '-' }}</span>
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_2 ?? '-' }}</span>
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_3 ?? '-' }}</span>
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_4 ?? '-' }}</span>
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_5 ?? '-' }}</span>
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_6 ?? '-' }}</span>
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_7 ?? '-' }}</span>
                                                <span class="text-green-600 font-bold w-4 text-center">{{ $alumno->nota_8 ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($alumno->empresa)
                                                <a href="{{ route('empresas.show', $alumno->empresa->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                    {{ $alumno->empresa->nombre }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic">No asignada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $alumno->dni_encriptado }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            No hay alumnos registrados en este curso para el año académico actual.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
