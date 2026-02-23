<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instituto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    

                    
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
                        <h3 class="text-lg font-bold">Listado de Alumnos</h3>
                        
                        <!-- Buscador y Filtros -->
                        <form method="GET" action="{{ route('alumnos.index') }}" class="flex flex-wrap gap-2 items-center" x-data="{ 
                            search: '{{ request('search') }}',
                            async performSearch() {
                                let url = new URL(window.location.href);
                                url.searchParams.set('search', this.search);
                                
                                // Push state to update URL without reload
                                window.history.pushState({}, '', url);

                                const response = await fetch(url, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                });
                                const html = await response.text();
                                document.getElementById('alumnos-table-body').innerHTML = html;
                            }
                        }">
                            <select name="curso_academico_id" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" onchange="this.form.submit()">
                                <option value="">Todos los Cursos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_academico_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->anyo }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="curso_id" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm w-full sm:w-auto" onchange="this.form.submit()">
                                <option value="">Todos los Módulos</option>
                                @foreach($cursosDisponibles as $c)
                                    <option value="{{ $c->id }}" {{ request('curso_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->modulo->nombre }} - {{ $c->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="flex w-full sm:w-auto">
                                <input type="text" name="search" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Buscar por nombre o DNI..." class="flex-1 sm:w-52 rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md transition duration-300">
                                    Buscar
                                </button>
                            </div>
                        </form>

                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'crear-alumno')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 self-start sm:self-auto">
                            Nuevo Alumno
                        </button>
                    </div>

                    {{-- Barra acciones selección --}}
                    <div id="bulk-action-bar"
                         class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg flex items-center justify-between transition-all"
                         style="display:none">
                        <span class="text-sm font-medium text-red-700">
                            <span id="selected-count">0</span> alumno(s) seleccionado(s)
                        </span>
                        <button type="button" onclick="confirmarEliminacion()"
                                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Eliminar seleccionados
                        </button>
                    </div>

                    {{-- Formulario oculto eliminación masiva --}}
                    <form id="bulk-delete-form" action="{{ route('alumnos.destroyBulk') }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                    <!-- Tabla Alumnos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" id="check-all"
                                               style="width:16px;height:16px;cursor:pointer;accent-color:#4f46e5;"
                                               title="Seleccionar todos"
                                               onchange="toggleAll(this)">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="alumnos-table-body" class="bg-white divide-y divide-gray-200">
                                @include('alumnos.partials.table-rows')
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $alumnos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación --}}
    <div id="confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar eliminación</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        ¿Estás seguro de que quieres eliminar <strong id="modal-count"></strong>?
                        Esta acción no se puede deshacer.
                    </p>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="cerrarModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="ejecutarEliminacion()"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>

    <script>
        function getChecked() {
            return [...document.querySelectorAll('.alumno-check:checked')];
        }

        function actualizarSeleccion() {
            const checked = getChecked();
            const bar = document.getElementById('bulk-action-bar');
            const countEl = document.getElementById('selected-count');
            const checkAll = document.getElementById('check-all');
            const total = document.querySelectorAll('.alumno-check').length;

            countEl.textContent = checked.length;
            bar.style.display = checked.length === 0 ? 'none' : 'flex';
            if (checkAll) {
                checkAll.indeterminate = checked.length > 0 && checked.length < total;
                checkAll.checked = checked.length === total && total > 0;
            }
        }

        function toggleAll(source) {
            document.querySelectorAll('.alumno-check').forEach(cb => {
                cb.checked = source.checked;
            });
            actualizarSeleccion();
        }

        function confirmarEliminacion() {
            const count = getChecked().length;
            if (count === 0) return;
            document.getElementById('modal-count').textContent =
                count + (count === 1 ? ' alumno' : ' alumnos');
            document.getElementById('confirm-modal').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
        }

        function ejecutarEliminacion() {
            const form = document.getElementById('bulk-delete-form');
            form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
            getChecked().forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            form.submit();
        }

        document.getElementById('confirm-modal').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });

        // Re-bind checkboxes tras actualizaciones AJAX de la tabla
        function bindCheckboxes() {
            document.querySelectorAll('.alumno-check').forEach(cb => {
                cb.onchange = actualizarSeleccion;
            });
        }
        bindCheckboxes();

        // Observar cambios en el tbody para re-hacer el binding cuando se recargan filas por AJAX
        const tableBody = document.getElementById('alumnos-table-body');
        if (tableBody) {
            new MutationObserver(bindCheckboxes).observe(tableBody, { childList: true, subtree: true });
        }
    </script>

    <!-- Modal crear alumno -->
    <x-modal name="crear-alumno" focusable maxWidth="3xl">
        <div class="p-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Añadir Nuevo Alumno') }}
            </h2>

            <form method="POST" action="{{ route('alumnos.store') }}">
                @csrf

                @include('alumnos.partials.form', ['alumno' => null, 'cursos' => $cursos])

                <div class="mt-8 flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2 mb-6">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 mr-8 mb-6">
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
