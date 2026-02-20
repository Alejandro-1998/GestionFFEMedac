@props(['empresa' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Nombre -->
    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $empresa?->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        <template x-if="typeof errors !== 'undefined' && errors.nombre">
            <span x-text="errors.nombre[0]" class="text-red-500 text-xs"></span>
        </template>
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $empresa?->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        <template x-if="typeof errors !== 'undefined' && errors.email">
            <span x-text="errors.email[0]" class="text-red-500 text-xs"></span>
        </template>
    </div>

    <!-- NIF -->
    <div>
        <label for="nif" class="block text-sm font-medium text-gray-700">NIF</label>
        <input type="text" name="nif" id="nif" value="{{ old('nif', $empresa?->nif) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @error('nif') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        <template x-if="typeof errors !== 'undefined' && errors.nif">
            <span x-text="errors.nif[0]" class="text-red-500 text-xs"></span>
        </template>
    </div>

    <!-- Dirección -->
    <div class="md:col-span-2">
        <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
        <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $empresa?->direccion) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @error('direccion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        <template x-if="typeof errors !== 'undefined' && errors.direccion">
            <span x-text="errors.direccion[0]" class="text-red-500 text-xs"></span>
        </template>
    </div>

    <!-- Teléfono -->
    <div>
        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $empresa?->telefono) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        <template x-if="typeof errors !== 'undefined' && errors.telefono">
            <span x-text="errors.telefono[0]" class="text-red-500 text-xs"></span>
        </template>
    </div>

    <!-- Módulos Formativos -->
    <div class="md:col-span-2">
        <span class="block text-sm font-medium text-gray-700 mb-2">Módulos Asociados <span class="text-red-500">*</span></span>
        
        @if($empresa)
            <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                @if($empresa->modulos->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($empresa->modulos as $modulo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $modulo->nombre }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No hay módulos asociados.</p>
                @endif
            </div>
        @else
            <!-- Create Mode: Editable -->
            @if(isset($modulos) && count($modulos) > 0)
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modulos as $modulo)
                        <div class="flex items-center">
                            <input type="checkbox" name="modulos[]" value="{{ $modulo->id }}" id="modulo_{{ $modulo->id }}" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                @if(old('modulos') && in_array($modulo->id, old('modulos'))) checked @endif>
                            <label for="modulo_{{ $modulo->id }}" class="ml-2 text-sm text-gray-700">
                                {{ $modulo->nombre }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-1">Seleccionar un módulo asociará automáticamente a la empresa con este.</p>
                @error('modulos') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                <template x-if="typeof errors !== 'undefined' && errors.modulos">
                    <span x-text="errors.modulos[0]" class="text-red-500 text-sm mt-1 block"></span>
                </template>
            @else
                <p class="text-red-500 text-sm">No hay módulos registrados en el sistema.</p>
            @endif
        @endif
    </div>
</div>
