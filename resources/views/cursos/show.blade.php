<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Curso Académico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('cursos.index') }}" class="text-blue-600 hover:underline flex items-center">
                            &larr; Volver a Cursos
                        </a>
                    </div>

                    <div class="flex justify-between items-start mb-6 border-b pb-6">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $curso->anyo }}</h3>

                        </div>
                    </div>


                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mt-6 mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-6 mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
                        </div>
                    @endif

                    <!-- Consolidated Alumnos Card -->
                    <div class="mt-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <!-- Header with Inline Import -->
                        <div class="px-6 py-4 bg-white border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                Listado de Alumnos
                                <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                                    {{ $curso->alumnos->count() }}
                                </span>
                            </h3>

                            <!-- Compact Import Form -->
                            <form action="{{ route('cursos.importarAlumnos', $curso->id) }}" method="POST" enctype="multipart/form-data" 
                                  class="flex items-center gap-3" x-data="{ fileName: '' }">
                                @csrf
                                <label class="cursor-pointer inline-flex items-center gap-2.5 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 font-medium hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all shadow-sm group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span x-text="fileName ? fileName : 'Seleccionar Excel'" class="truncate max-w-[140px]"></span>
                                    <input type="file" name="archivo_excel" required accept=".xlsx, .xls, .csv, .txt" class="hidden" 
                                           @change="fileName = $event.target.files[0].name" />
                                </label>
                                
                                <button type="submit" class="inline-flex items-center justify-center p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!fileName" title="Subir Archivo">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Importar
                                </button>
                            </form>
                            
                            <a href="{{ route('cursos.exportarPdf', $curso->id) }}" class="inline-flex items-center justify-center p-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 shadow-md hover:shadow-lg transition-all transform active:scale-95 ml-2" title="Descargar PDF">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                PDF
                            </a>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 w-48">Alumno/a</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nota</th>
                                        <!-- Miniature Headers for Notas -->
                                        @foreach(['Nota 1', 'Nota 2', 'Nota 3', 'Nota 4', 'Nota 5', 'Nota 6', 'Nota 7', 'Nota 8'] as $header)
                                            <th class="px-2 py-3 text-center font-semibold text-xs text-gray-400 uppercase tracking-wider">{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse ($curso->alumnos as $alumno)
                                        <tr class="hover:bg-gray-50/80 transition-colors group">
                                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900 sticky left-0 bg-white group-hover:bg-gray-50 transition-colors shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">{{ $alumno->nombre_completo }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-500 font-mono text-xs">{{ $alumno->dni }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-600 truncate max-w-[12rem] text-xs">
                                                @if($alumno->empresa)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                                        {{ $alumno->empresa->nombre }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-700">{{ $alumno->nota_media }}</td>
                                            
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs border-l border-gray-50">{{ $alumno->nota_1 ?? '-' }}</td>
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs">{{ $alumno->nota_2 ?? '-' }}</td>
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs">{{ $alumno->nota_3 ?? '-' }}</td>
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs">{{ $alumno->nota_4 ?? '-' }}</td>
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs">{{ $alumno->nota_5 ?? '-' }}</td>
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs">{{ $alumno->nota_6 ?? '-' }}</td>
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs">{{ $alumno->nota_7 ?? '-' }}</td>
                                            <td class="px-2 py-3 whitespace-nowrap text-center text-gray-500 text-xs">{{ $alumno->nota_8 ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                    <p class="text-base font-medium text-gray-900">No hay alumnos todavía</p>
                                                    <p class="text-sm text-gray-500 mt-1">Utiliza el botón de arriba ("Seleccionar Excel") para añadir alumnos.</p>
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
    </div>
</x-app-layout>
