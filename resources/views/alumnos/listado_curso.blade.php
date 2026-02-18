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
                    
                    <div class="mb-6 flex justify-between items-center bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-100">
                        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-900 flex items-center font-medium transition-colors duration-200">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Volver
                        </a>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-600 text-sm font-medium mr-2 bg-white px-3 py-1 rounded border border-gray-200 shadow-sm">
                                Curso: <span class="text-indigo-600 font-bold">{{ $cursoAcademico->anyo }}</span>
                            </span>
                            
                            <a href="{{ route('alumnos.exportar-pdf', ['curso' => $curso->id, 'cursoAcademico' => $cursoAcademico->id]) }}" 
                               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md inline-flex items-center transition-all shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                PDF
                            </a>

                            <form action="{{ route('alumnos.importar', ['curso' => $curso->id, 'cursoAcademico' => $cursoAcademico->id]) }}" method="POST" enctype="multipart/form-data" class="inline-flex m-0">
                                @csrf
                                <label for="fichero_alumnos" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 px-4 rounded-md inline-flex items-center transition-all shadow-sm hover:shadow-md cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Importar CSV
                                </label>
                                <input type="file" name="fichero_alumnos" id="fichero_alumnos" class="hidden" onchange="this.form.submit()">
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Alumno
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nota Media
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Notas
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Empresa
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        DNI
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($alumnos as $alumno)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <a href="{{ route('alumnos.show', $alumno->id) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                                                    {{ $alumno->nombre_completo }}
                                                </a>
                                                <span class="text-xs text-gray-500">{{ $alumno->email }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($alumno->nota_media !== null)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $alumno->nota_media >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ number_format($alumno->nota_media, 2) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="inline-flex flex-row border border-gray-200 rounded-md overflow-hidden bg-white shadow-sm h-6">
                                                @foreach(['nota_1', 'nota_2', 'nota_3', 'nota_4', 'nota_5', 'nota_6', 'nota_7', 'nota_8'] as $nota)
                                                    <div class="w-6 flex items-center justify-center border-r border-gray-100 last:border-r-0 text-xs {{ isset($alumno->$nota) ? ($alumno->$nota >= 5 ? 'bg-green-50 text-green-700 font-medium' : 'bg-red-50 text-red-700 font-medium') : 'text-gray-300' }}" title="{{ ucfirst(str_replace('_', ' ', $nota)) }}">
                                                        {{ $alumno->$nota ?? '-' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($alumno->empresa)
                                                <a href="{{ route('empresas.show', $alumno->empresa->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                    {{ Str::limit($alumno->empresa->nombre, 20) }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic text-xs">No asignada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-medium rounded-md bg-gray-100 text-gray-600 border border-gray-200">
                                                {{ $alumno->dni_encriptado }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                <p>No hay alumnos registrados en este curso para el año académico actual.</p>
                                            </div>
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
