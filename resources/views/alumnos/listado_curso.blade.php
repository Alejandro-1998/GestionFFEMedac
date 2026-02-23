<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Alumnos - {{ $curso->nombre }} ({{ $curso->modulo->nombre }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensajes flash --}}
                    @if (session('success'))
                        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Barra de acciones --}}
                    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-100 gap-3">
                        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-900 flex items-center font-medium transition-colors duration-200">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Volver
                        </a>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-gray-600 text-sm font-medium bg-white px-3 py-1 rounded border border-gray-200 shadow-sm">
                                Curso: <span class="text-indigo-600 font-bold">{{ $cursoAcademico->anyo }}</span>
                            </span>

                            <a href="{{ route('alumnos.exportar-pdf', ['curso' => $curso->id, 'cursoAcademico' => $cursoAcademico->id]) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md inline-flex items-center transition-all shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                PDF
                            </a>

                            <form action="{{ route('alumnos.importar', ['curso' => $curso->id, 'cursoAcademico' => $cursoAcademico->id]) }}" method="POST" enctype="multipart/form-data" class="inline-flex m-0">
                                @csrf
                                <label for="fichero_alumnos" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 px-4 rounded-md inline-flex items-center transition-all shadow-sm hover:shadow-md cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Importar CSV
                                </label>
                                <input type="file" name="fichero_alumnos" id="fichero_alumnos" class="hidden" onchange="this.form.submit()">
                            </form>
                        </div>
                    </div>

                    {{-- Barra acciones selección (oculta por defecto) --}}
                    <div id="bulk-action-bar"
                         class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg flex items-center justify-between transition-all" style="display:none">
                        <span class="text-sm font-medium text-red-700">
                            <span id="selected-count">0</span> alumno(s) seleccionado(s)
                        </span>
                        <button type="button" onclick="confirmarEliminacion()"
                                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-all shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Eliminar seleccionados
                        </button>
                    </div>

                    {{-- Formulario oculto para eliminación masiva --}}
                    <form id="bulk-delete-form" action="{{ route('alumnos.destroyBulk') }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                        {{-- Los IDs se inyectan dinámicamente por JS --}}
                    </form>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- Checkbox seleccionar todos --}}
                                    <th scope="col" class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" id="check-all"
                                               style="width:16px;height:16px;cursor:pointer;accent-color:#4f46e5;"
                                               title="Seleccionar todos"
                                               onchange="toggleAll(this)">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Alumno
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nota Media
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Notas
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Empresa
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        DNI
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($alumnos as $alumno)
                                    <tr class="hover:bg-gray-50 transition-colors alumno-row" data-id="{{ $alumno->id }}">
                                        {{-- Checkbox individual --}}
                                        <td class="px-4 py-4 w-10 text-center">
                                            <input type="checkbox"
                                                   class="alumno-check"
                                                   style="width:16px;height:16px;cursor:pointer;accent-color:#4f46e5;"
                                                   value="{{ $alumno->id }}"
                                                   onchange="actualizarSeleccion()">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <a href="{{ route('alumnos.show', $alumno->id) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                                                    {{ $alumno->nombre_completo }}
                                                </a>
                                                <span class="text-xs text-gray-500">{{ $alumno->email }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($alumno->nota_media !== null)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $alumno->nota_media >= 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ number_format($alumno->nota_media, 2) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex flex-row flex-wrap gap-1 justify-center">
                                                @foreach(['nota_1', 'nota_2', 'nota_3', 'nota_4', 'nota_5', 'nota_6', 'nota_7', 'nota_8'] as $i => $nota)
                                                    <span class="inline-flex items-center justify-center w-9 h-6 rounded text-xs font-semibold border
                                                        {{ isset($alumno->$nota)
                                                            ? ($alumno->$nota >= 5 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200')
                                                            : 'bg-gray-50 text-gray-300 border-gray-200' }}"
                                                        title="Nota {{ $i + 1 }}">
                                                        {{ isset($alumno->$nota) ? number_format($alumno->$nota, 1) : '-' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($alumno->empresa)
                                                <a href="{{ route('empresas.show', $alumno->empresa->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                    {{ Str::limit($alumno->empresa->nombre, 20) }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic text-xs">No asignada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-medium rounded-md bg-gray-100 text-gray-600 border border-gray-200">
                                                {{ $alumno->dni_encriptado }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                <p>No hay alumnos registrados en este curso para el año académico actual.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
            checkAll.indeterminate = checked.length > 0 && checked.length < total;
            checkAll.checked = checked.length === total && total > 0;
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
            // Limpiar inputs anteriores
            form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
            // Agregar los IDs seleccionados
            getChecked().forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            form.submit();
        }

        // Cerrar modal al pulsar fuera
        document.getElementById('confirm-modal').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });
    </script>
</x-app-layout>
