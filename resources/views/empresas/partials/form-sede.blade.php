@props(['sede' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Nombre -->
    <div>
        <label for="sede_nombre" class="block text-sm font-medium text-gray-700">Nombre de la Sede</label>
        <input type="text" name="nombre" id="sede_nombre" value="{{ old('nombre', $sede?->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Ubicación (Ciudad/País) -->
    <div>
        <label for="sede_ubicacion" class="block text-sm font-medium text-gray-700">Ubicación (Ciudad/País)</label>
        <input type="text" name="ubicacion" id="sede_ubicacion" value="{{ old('ubicacion', $sede?->ubicacion) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Dirección -->
    <div>
        <label for="sede_direccion" class="block text-sm font-medium text-gray-700">Dirección Completa</label>
        <input type="text" name="direccion" id="sede_direccion" value="{{ old('direccion', $sede?->direccion) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Teléfono -->
    <div>
        <label for="sede_telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input type="text" name="telefono" id="sede_telefono" value="{{ old('telefono', $sede?->telefono) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>
</div>
