<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Convenios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex space-x-4 mb-6 border-b pb-4">
                        <a href="{{ route('convenios.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300">
                            Listado de Convenios
                        </a>
                        <a href="{{ route('convenios.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition duration-300">
                            Nuevo Convenio
                        </a>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-6">Crear Nuevo Convenio</h3>

                    <form method="POST" action="{{ route('convenios.store') }}" x-data="convenioForm()">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Módulo -->
                            <div>
                                <label for="modulo_id" class="block text-sm font-medium text-gray-700">Módulo</label>
                                <select id="modulo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required x-model="selectedModuloId" @change="onModuloChange">
                                    <option value="">Seleccione Módulo</option>
                                    <template x-for="modulo in modulos" :key="modulo.id">
                                        <option :value="modulo.id" x-text="modulo.nombre"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Curso (1º / 2º) -->
                            <div>
                                <label for="curso_id" class="block text-sm font-medium text-gray-700">Curso</label>
                                <select id="curso_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required :disabled="!selectedModuloId" x-model="selectedCursoId" @change="onCursoChange">
                                    <option value="">Seleccione Curso</option>
                                    <template x-for="curso in cursosDelModulo" :key="curso.id">
                                        <option :value="curso.id" x-text="curso.nombre"></option>
                                    </template>
                                </select>
                                <p x-show="!selectedModuloId" class="text-xs text-gray-500 mt-1">Seleccione un módulo primero.</p>
                            </div>

                            <!-- Alumno -->
                            <div>
                                <label for="alumno_id" class="block text-sm font-medium text-gray-700">Alumno</label>
                                <select name="alumno_id" id="alumno_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required :disabled="!selectedCursoId">
                                    <option value="">Seleccione un Alumno</option>
                                    <template x-for="alumno in alumnosDelCurso" :key="alumno.id">
                                        <option :value="alumno.id" x-text="alumno.nombre_completo"></option>
                                    </template>
                                </select>
                                <p x-show="!selectedCursoId" class="text-xs text-gray-500 mt-1">Seleccione un curso primero.</p>
                                @error('alumno_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Empresa -->
                            <div>
                                <label for="empresa_id" class="block text-sm font-medium text-gray-700">Empresa</label>
                                <select name="empresa_id" id="empresa_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleccione Empresa</option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('empresa_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('convenios.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Crear Convenio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function convenioForm() {
            return {
                modulos: @json($modulosJson),
                allAlumnos: @json($alumnos),
                selectedModuloId: '',
                selectedCursoId: '',
                cursosDelModulo: [],
                alumnosDelCurso: [],

                onModuloChange() {
                    this.selectedCursoId = '';
                    this.alumnosDelCurso = [];
                    const modulo = this.modulos.find(m => m.id == this.selectedModuloId);
                    this.cursosDelModulo = modulo ? modulo.cursos : [];
                },

                onCursoChange() {
                    this.alumnosDelCurso = this.allAlumnos.filter(a => a.curso_id == this.selectedCursoId);
                }
            }
        }
    </script>
</x-app-layout>
