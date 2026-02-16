    <!-- Nombre Completo -->
    <div>
        <label for="nombre_completo" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
        <input type="text" name="nombre_completo" id="nombre_completo" value="{{ old('nombre_completo', $alumno->nombre_completo ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @error('nombre_completo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- DNI -->
    <div class="mt-4">
        <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
        <input type="text" name="dni" id="dni" value="{{ old('dni', $alumno->dni ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Curso Académico -->
    <div class="mt-4">
        <label for="curso_academico_id" class="block text-sm font-medium text-gray-700">Curso Académico</label>
        <select name="curso_academico_id" id="curso_academico_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Ninguno --</option>
            @foreach($cursos as $curso)
                <option value="{{ $curso->id }}" {{ (old('curso_academico_id', $alumno->curso_academico_id ?? '') == $curso->id) ? 'selected' : '' }}>
                    {{ $curso->anyo }}
                </option>
            @endforeach
        </select>
        @error('curso_academico_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>



    <!-- Nota Media -->
    <div class="mt-4">
        <label for="nota_media" class="block text-sm font-medium text-gray-700">Nota Media</label>
        <input type="number" step="0.01" name="nota_media" id="nota_media" value="{{ old('nota_media', $alumno->nota_media ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed shadow-sm focus:border-indigo-500 focus:ring-indigo-500" readonly>
        <p class="text-xs text-gray-500 mt-1">Se calcula autom&aacute;ticamente al guardar.</p>
        @error('nota_media') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Calificaciones -->
    <div class="mt-6 border-t pt-4">
        <h4 class="text-sm font-medium text-gray-900 mb-2">Calificaciones</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @for($i = 1; $i <= 8; $i++)
                <div>
                    <label for="nota_{{ $i }}" class="block text-xs font-medium text-gray-500">Nota {{ $i }}</label>
                    <input type="number" step="0.01" min="0" max="10" name="nota_{{ $i }}" id="nota_{{ $i }}" value="{{ old('nota_'.$i, $alumno->{'nota_'.$i} ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            @endfor
        </div>
    </div>
</div>
