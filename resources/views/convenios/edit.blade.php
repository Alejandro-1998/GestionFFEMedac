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
                    
                    <!-- Navegación de Sección Convenios -->
                    <div class="flex space-x-4 mb-6 border-b pb-4">
                        <a href="{{ route('convenios.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300">
                            Listado de Convenios
                        </a>
                        <span class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition duration-300">
                            Editar Convenio #{{ $convenio->id }}
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Completar / Editar Convenio</h3>

                    <form method="POST" action="{{ route('convenios.update', $convenio->id) }}" x-data="convenioForm()">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Datos Inmutables / Solo Lectura -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="font-bold text-gray-700 mb-2">Datos Principales</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <span class="block text-sm font-medium text-gray-500">Alumno</span>
                                        <span class="block font-semibold">{{ $convenio->alumno->nombre_completo }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-500">Empresa</span>
                                        <span class="block font-semibold">{{ $convenio->empresa->nombre }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-500">Curso Académico</span>
                                        <span class="block font-semibold">{{ $convenio->curso->anyo }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                                <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="asignada" {{ old('estado', $convenio->estado) == 'asignada' ? 'selected' : '' }}>Asignada</option>
                                    <option value="en_proceso" {{ old('estado', $convenio->estado) == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="finalizada" {{ old('estado', $convenio->estado) == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                    <option value="cancelada" {{ old('estado', $convenio->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Sede (Filtered) -->
                            <div>
                                <label for="sede_id" class="block text-sm font-medium text-gray-700">Sede</label>
                                <select name="sede_id" id="sede_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleccione Sede</option>
                                    @foreach($convenio->empresa->sedes as $sede)
                                        <option value="{{ $sede->id }}" {{ old('sede_id', $convenio->sede_id) == $sede->id ? 'selected' : '' }}>
                                            {{ $sede->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sede_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tutor Laboral (Filtered) -->
                            <div>
                                <label for="empleado_id" class="block text-sm font-medium text-gray-700">Tutor Laboral</label>
                                <select name="empleado_id" id="empleado_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleccione Tutor</option>
                                    @foreach($convenio->empresa->empleados as $empleado)
                                        <option value="{{ $empleado->id }}" {{ old('empleado_id', $convenio->empleado_id) == $empleado->id ? 'selected' : '' }}>
                                            {{ $empleado->nombre }} {{ $empleado->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('empleado_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Profesor Tutor -->
                            <div>
                                <label for="profesor_id" class="block text-sm font-medium text-gray-700">Profesor Tutor</label>
                                <select name="profesor_id" id="profesor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleccione Profesor</option>
                                    @foreach($profesores as $profesor)
                                        <option value="{{ $profesor->id }}" {{ old('profesor_id', $convenio->profesor_id) == $profesor->id ? 'selected' : '' }}>{{ $profesor->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('profesor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Fechas y Horas -->
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', $convenio->fecha_inicio ? $convenio->fecha_inicio->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('fecha_inicio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', $convenio->fecha_fin ? $convenio->fecha_fin->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('fecha_fin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="total_horas" class="block text-sm font-medium text-gray-700">Total Horas</label>
                                <input type="number" name="total_horas" id="total_horas" value="{{ old('total_horas', $convenio->total_horas) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('total_horas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('convenios.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Actualizar Convenio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
