@props(['empleado' => null, 'sedes' => [], 'suffix' => ''])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- DNI/Pasaporte -->
    <div>
        <label for="dni_pasaporte_{{ $suffix }}" class="block text-sm font-medium text-gray-700">DNI / Pasaporte</label>
        <input type="text" name="dni_pasaporte" id="dni_pasaporte_{{ $suffix }}" value="{{ old('dni_pasaporte', $empleado?->dni_pasaporte) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Cargo -->
    <div>
        <label for="cargo_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Cargo</label>
        <input type="text" name="cargo" id="cargo_{{ $suffix }}" value="{{ old('cargo', $empleado?->cargo) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Nombre -->
    <div>
        <label for="nombre_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" name="nombre" id="nombre_{{ $suffix }}" value="{{ old('nombre', $empleado?->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Apellido 1 -->
    <div>
        <label for="apellido_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Primer Apellido</label>
        <input type="text" name="apellido" id="apellido_{{ $suffix }}" value="{{ old('apellido', $empleado?->apellido) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Apellido 2 -->
    <div>
        <label for="apellido2_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Segundo Apellido</label>
        <input type="text" name="apellido2" id="apellido2_{{ $suffix }}" value="{{ old('apellido2', $empleado?->apellido2) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <!-- Email -->
    <div>
        <label for="email_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email_{{ $suffix }}" value="{{ old('email', $empleado?->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <!-- Fecha de Nacimiento -->
    <div>
        <label for="fecha_nacimiento_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento_{{ $suffix }}" value="{{ old('fecha_nacimiento', $empleado?->fecha_nacimiento) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Teléfono Responsable -->
    <div class="md:col-span-2">
        <label for="telefono_responsable_laboral_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Teléfono Responsable Laboral</label>
        <input type="text" name="telefono_responsable_laboral" id="telefono_responsable_laboral_{{ $suffix }}" value="{{ old('telefono_responsable_laboral', $empleado?->telefono_responsable_laboral) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>

    <!-- Sede (Opcional) -->
    <div class="md:col-span-2">
        <label for="sede_id_{{ $suffix }}" class="block text-sm font-medium text-gray-700">Sede</label>
        <select name="sede_id" id="sede_id_{{ $suffix }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Sin asignar --</option>
            @if(isset($sedes))
                @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}" {{ (string)old('sede_id', $empleado?->sede_id) === (string)$sede->id ? 'selected' : '' }}>
                        {{ $sede->nombre }} ({{ $sede->ubicacion }})
                    </option>
                @endforeach

                {{-- Handle case where assigned Sede is not in the company list (Data Inconsistency) --}}
                @php
                    $currentSedeId = old('sede_id', $empleado?->sede_id);
                    $isInList = $sedes->contains('id', $currentSedeId);
                @endphp
                
                @if($currentSedeId && !$isInList)
                    @if($empleado && $empleado->sede)
                        <option value="{{ $currentSedeId }}" selected>
                            {{ $empleado->sede->nombre }} ({{ $empleado->sede->ubicacion }}) - [Asignada actualmente]
                        </option>
                    @else
                        <option value="{{ $currentSedeId }}" selected>Sede ID: {{ $currentSedeId }} (No asignada a esta empresa)</option>
                    @endif
                @endif
            @endif
        </select>
    </div>
    
    <!-- Activo -->
    <div class="md:col-span-2 flex items-center">
        <input type="hidden" name="activo" value="0">
        <input type="checkbox" name="activo" id="activo_{{ $suffix }}" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('activo', $empleado?->activo ?? true) ? 'checked' : '' }}>
        <label for="activo_{{ $suffix }}" class="ml-2 block text-sm font-medium text-gray-700">Empleado Activo</label>
    </div>
</div>
