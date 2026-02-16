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

    <!-- CIF -->
    <div>
        <label for="cif" class="block text-sm font-medium text-gray-700">CIF</label>
        <input type="text" name="cif" id="cif" value="{{ old('cif', $empresa?->cif) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @error('cif') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        <template x-if="typeof errors !== 'undefined' && errors.cif">
            <span x-text="errors.cif[0]" class="text-red-500 text-xs"></span>
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

    <!-- Ciclos Formativos -->
    <div class="md:col-span-2">
        <span class="block text-sm font-medium text-gray-700 mb-2">Ciclos Formativos Asociados <span class="text-red-500">*</span></span>
        
        @if($empresa)
            <!-- Edit Mode: Read Only -->
            <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                @if($empresa->ciclos->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($empresa->ciclos as $ciclo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $ciclo->nombre }}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Los ciclos no se pueden modificar en la edición.</p>
                @else
                    <p class="text-sm text-gray-500">No hay ciclos asociados.</p>
                @endif
            </div>
        @else
            <!-- Create Mode: Editable -->
            @if(isset($ciclos) && count($ciclos) > 0)
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($ciclos as $ciclo)
                        <div class="flex items-center">
                            <input type="checkbox" name="ciclos[]" value="{{ $ciclo->id }}" id="ciclo_{{ $ciclo->id }}" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                @if(old('ciclos') && in_array($ciclo->id, old('ciclos'))) checked @endif>
                            <label for="ciclo_{{ $ciclo->id }}" class="ml-2 text-sm text-gray-700">{{ $ciclo->nombre }}</label>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-1">Seleccionar un ciclo asociará automáticamente todos los cursos de ese grado.</p>
                @error('ciclos') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                <template x-if="typeof errors !== 'undefined' && errors.ciclos">
                    <span x-text="errors.ciclos[0]" class="text-red-500 text-sm mt-1 block"></span>
                </template>
            @else
                <p class="text-red-500 text-sm">No hay ciclos registrados en el sistema.</p>
            @endif
        @endif
    </div>
</div>
