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

    <!-- Email -->
    <div class="mt-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
        <div class="mt-1 flex rounded-md shadow-sm">
            <input type="text" name="email" id="email" value="{{ old('email', $alumno ? str_replace('@alu.medac.es', '', $alumno->email) : '') }}" class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="nombre.apellido" required>
            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                @alu.medac.es
            </span>
        </div>
        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Selección de Curso Académico / Módulo / Curso -->
    <div x-data="{
        cursosAcademicos: {{ $cursos->toJson() }},
        selectedAnyo: {{ old('curso_academico_id', $alumno->curso_academico_id ?? 'null') }},
        selectedModulo: {{ $alumno && $alumno->curso ? $alumno->curso->modulo_id : 'null' }},
        selectedCurso: {{ old('curso_id', $alumno->curso_id ?? 'null') }},
        
        get modulosDisponibles() {
            if (!this.selectedAnyo) return [];
            const ca = this.cursosAcademicos.find(c => c.id == this.selectedAnyo);
            return ca ? ca.modulos : [];
        },
        
        get cursosDisponibles() {
            if (!this.selectedModulo || !this.selectedAnyo) return [];
            // Necesitamos encontrar el módulo dentro del año seleccionado
            const ca = this.cursosAcademicos.find(c => c.id == this.selectedAnyo);
            if (!ca) return [];
            const mod = ca.modulos.find(m => m.id == this.selectedModulo);
            return mod ? mod.cursos : [];
        },

        init() {
            // Si ya tenemos valores seleccionados (Edición), no necesitamos deducir nada,
            // pero si estamos en un error de validación donde old('curso_id') existe pero modulo no (porque no se envía),
            // entonces sí necesitamos deducir el módulo.
            
            if (this.selectedCurso && !this.selectedModulo) {
                 // Deducir módulo y año si falta alguno
                 for (const ca of this.cursosAcademicos) {
                    // Si ya tenemos año, solo buscamos en ese año (optimización)
                    if (this.selectedAnyo && ca.id != this.selectedAnyo) continue;

                    if (ca.modulos) {
                        for (const mod of ca.modulos) {
                            const cursoFound = mod.cursos.find(c => c.id == this.selectedCurso);
                            if (cursoFound) {
                                if (!this.selectedAnyo) this.selectedAnyo = ca.id;
                                // Retrasar asignación del módulo para asegurar que el watcher de anyo actualice la lista
                                setTimeout(() => {
                                     this.selectedModulo = mod.id;
                                }, 50);
                                return;
                            }
                        }
                    }
                 }
            }
            
            // Si es 'Nuevo Alumno' (sin selección previa), seleccionar el año actual
            if (!this.selectedAnyo && !this.selectedCurso) {
                 const actual = this.cursosAcademicos.find(c => c.actual);
                 if (actual) {
                     this.selectedAnyo = actual.id;
                 }
            }
        }
    }" class="space-y-4 mt-4 bg-gray-50 p-4 rounded-md border border-gray-200">
        
        <h4 class="text-sm font-medium text-gray-900 border-b pb-2 mb-3">Datos Académicos</h4>

        <!-- 1. Curso Académico -->
        <div>
            <label for="select_anyo" class="block text-sm font-medium text-gray-700">Curso Académico</label>
            <select name="curso_academico_id" x-model="selectedAnyo" id="select_anyo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">-- Seleccionar Año --</option>
                <template x-for="ca in cursosAcademicos" :key="ca.id">
                    <option :value="ca.id" x-text="ca.anyo + (ca.actual ? ' (Actual)' : '')"></option>
                </template>
            </select>
        </div>

        <!-- 2. Módulo (Ciclo) -->
        <div>
            <label for="select_modulo" class="block text-sm font-medium text-gray-700">Módulo (Ciclo)</label>
            <select x-model="selectedModulo" id="select_modulo" :disabled="!selectedAnyo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-400">
                <option value="">-- Seleccionar Módulo --</option>
                <template x-for="mod in modulosDisponibles" :key="mod.id">
                    <option :value="mod.id" x-text="mod.nombre"></option>
                </template>
            </select>
        </div>

        <!-- 3. Curso (1º/2º) -->
        <div>
            <label for="curso_id" class="block text-sm font-medium text-gray-700">Año del Curso (1º / 2º)</label>
            <select name="curso_id" id="curso_id" x-model="selectedCurso" :disabled="!selectedModulo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-400" required>
                <option value="">-- Seleccionar Curso --</option>
                <template x-for="curso in cursosDisponibles" :key="curso.id">
                    <option :value="curso.id" x-text="curso.nombre"></option>
                </template>
            </select>
            @error('curso_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>



    <!-- Nota Media -->


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
