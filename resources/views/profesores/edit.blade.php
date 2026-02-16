<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Profesor') }}: {{ $profesor->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('profesores.update', $profesor->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $profesor->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $profesor->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Contraseña (Opcional) -->
                        <div class="mb-4 border-t pt-4 mt-4">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Cambiar Contraseña (Opcional)</h4>
                            <p class="text-sm text-gray-500 mb-2">Dejar en blanco si no se desea cambiar.</p>
                            
                            <label for="password" class="block text-sm font-medium text-gray-700 mt-2">Nueva Contraseña</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />

                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mt-2">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Asignación de Cursos -->
                        <div class="mb-6 border-t pt-4 mt-4">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Asignar Cursos Académicos</h4>
                            <p class="text-sm text-gray-500 mb-4">Selecciona los cursos que podrá gestionar este profesor.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($cursos as $curso)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="curso_{{ $curso->id }}" name="cursos[]" type="checkbox" value="{{ $curso->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            {{ in_array($curso->id, old('cursos', $cursosAsignados)) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="curso_{{ $curso->id }}" class="font-medium text-gray-700">{{ $curso->anyo }}</label>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No hay cursos académicos disponibles creados en el sistema.</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('cursos')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('profesores.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Actualizar Profesor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
