<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Convenios') }}
        </h2>
    </x-slot>

    <div x-data="configModal()">

    <!-- Toast de notificación -->
    <div
        x-show="toastVisible"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        :class="toastType === 'error' ? 'bg-red-600' : 'bg-green-600'"
        class="fixed bottom-6 right-6 z-50 text-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 max-w-sm"
        style="display:none;"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span x-text="toastMessage" class="text-sm font-medium"></span>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Navegación de Sección Convenios -->
                    <div class="flex flex-wrap gap-2 mb-6 border-b pb-4">
                        <a href="{{ route('convenios.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition duration-300">
                            Listado de Convenios
                        </a>
                        <a href="{{ route('convenios.create') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300">
                            Nuevo Convenio
                        </a>
                        <button @click="openConfigModal()" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            Configuración de Prácticas
                        </button>
                        <form id="form-horas-estandar" method="POST" action="{{ route('convenios.establecerHoras') }}" class="inline">
                            @csrf
                            <button type="button" @click="confirmHorasOpen = true" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-3 py-2 rounded-md font-medium transition duration-300 flex items-center text-sm" title="Actualizar todas las horas">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                                Aplicar Configuración de Prácticas
                            </button>
                        </form>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6 gap-3">
                        <h3 class="text-lg font-bold text-gray-800">Listado de Convenios</h3>
                        
                        <!-- Filtros y Buscador -->
                        <form method="GET" action="{{ route('convenios.index') }}" class="flex flex-wrap gap-2 items-center w-full sm:w-auto">
                            
                            <!-- Filtro Curso -->
                            <select name="curso_academico_id" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" onchange="this.form.submit()">
                                <option value="">Todos los Cursos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_academico_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->anyo }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Filtro Estado -->
                            <select name="estado" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" onchange="this.form.submit()">
                                <option value="">Todos los Estados</option>
                                <option value="asignada" {{ request('estado') == 'asignada' ? 'selected' : '' }}>Asignada</option>
                                <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>

                            <!-- Buscador -->
                            <div class="flex w-full sm:w-auto">
                                <input type="text" name="search" placeholder="Buscar por alumno o empresa..." value="{{ request('search') }}" class="flex-1 sm:w-48 rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md transition duration-300 text-sm font-medium">
                                    Buscar
                                </button>
                            </div>

                            @if(request('search') || request('curso_academico_id') || request('estado'))
                                <a href="{{ route('convenios.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-md transition duration-300 flex items-center text-sm">
                                    Limpiar
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alumno</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($convenios as $convenio)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('alumnos.show', $convenio->alumno->id) }}" class="flex items-center hover:bg-gray-50 transition duration-150 ease-in-out p-1 rounded-md group">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-3 group-hover:bg-indigo-200">
                                                    {{ substr($convenio->alumno->nombre_completo, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                        {{ $convenio->alumno->nombre_completo }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $convenio->alumno->dni }}</div>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('empresas.show', $convenio->empresa->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                                {{ $convenio->empresa->nombre }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $convenio->curso->anyo ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $convenio->estado ?? 'Activo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('convenios.show', $convenio->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver Detalles</a>
                                            <a href="{{ route('convenios.edit', $convenio->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Editar</a>
                                            <form action="{{ route('convenios.destroy', $convenio->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este convenio?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No se encontraron convenios.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $convenios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Config Modal -->
    <div x-show="isOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Configuración de Convenios
                            </h3>
                            <div class="mt-4 space-y-5">
                                <div class="border-b pb-4">
                                    <p class="text-sm font-semibold text-gray-600 mb-3">Horas de prácticas</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="total_horas_1" class="block text-sm font-medium text-gray-700">Horas 1º</label>
                                            <input type="number" x-model="total_horas_1" id="total_horas_1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label for="total_horas_2" class="block text-sm font-medium text-gray-700">Horas 2º</label>
                                            <input type="number" x-model="total_horas_2" id="total_horas_2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                </div>

                                <div class="border-b pb-4">
                                    <p class="text-sm font-semibold text-gray-600 mb-3">Fechas de prácticas 1º</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="fecha_inicio_1" class="block text-sm font-medium text-gray-700">Fecha inicio</label>
                                            <input type="date" x-model="fecha_inicio_1" id="fecha_inicio_1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label for="fecha_fin_1" class="block text-sm font-medium text-gray-700">Fecha fin</label>
                                            <input type="date" x-model="fecha_fin_1" id="fecha_fin_1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-600 mb-3">Fechas de prácticas 2º</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="fecha_inicio_2" class="block text-sm font-medium text-gray-700">Fecha inicio</label>
                                            <input type="date" x-model="fecha_inicio_2" id="fecha_inicio_2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label for="fecha_fin_2" class="block text-sm font-medium text-gray-700">Fecha fin</label>
                                            <input type="date" x-model="fecha_fin_2" id="fecha_fin_2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="saveConfig()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar
                    </button>
                    <button type="button" @click="closeConfigModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación: Aplicar Horas Estándar -->
    <div x-show="confirmHorasOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="confirmHorasOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div x-show="confirmHorasOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-lg shadow-xl p-6 max-w-md w-full z-10">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Aplicar Configuración de Prácticas</h3>
                        <p class="mt-2 text-sm text-gray-600">¿Estás seguro de que deseas aplicar las horas y fechas de prácticas de <strong>todos los convenios</strong> según la configuración global? Esta acción sobreescribirá los valores actuales.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="confirmHorasOpen = false" class="px-4 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="button" @click="confirmHorasOpen = false; document.getElementById('form-horas-estandar').submit()" class="px-4 py-2 rounded-md bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 transition">
                        Sí, aplicar
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        function configModal() {
            return {
                isOpen: false,
                confirmHorasOpen: false,
                toastVisible: false,
                toastMessage: '',
                toastType: 'success',
                toastTimer: null,
                total_horas_1: 0,
                total_horas_2: 0,
                fecha_inicio_1: '',
                fecha_fin_1: '',
                fecha_inicio_2: '',
                fecha_fin_2: '',
                openConfigModal() {
                    this.isOpen = true;
                    this.fetchConfig();
                },
                closeConfigModal() {
                    this.isOpen = false;
                },
                showToast(message, type = 'success') {
                    clearTimeout(this.toastTimer);
                    this.toastMessage = message;
                    this.toastType = type;
                    this.toastVisible = true;
                    this.toastTimer = setTimeout(() => { this.toastVisible = false; }, 4000);
                },
                fetchConfig() {
                    fetch('{{ route("configuracion.index") }}')
                        .then(response => response.json())
                        .then(data => {
                            this.total_horas_1 = data.total_horas_1;
                            this.total_horas_2 = data.total_horas_2;
                            this.fecha_inicio_1 = data.fecha_inicio_1 || '';
                            this.fecha_fin_1    = data.fecha_fin_1 || '';
                            this.fecha_inicio_2 = data.fecha_inicio_2 || '';
                            this.fecha_fin_2    = data.fecha_fin_2 || '';
                        });
                },
                saveConfig() {
                    fetch('{{ route("configuracion.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            total_horas_1:  this.total_horas_1,
                            total_horas_2:  this.total_horas_2,
                            fecha_inicio_1: this.fecha_inicio_1,
                            fecha_fin_1:    this.fecha_fin_1,
                            fecha_inicio_2: this.fecha_inicio_2,
                            fecha_fin_2:    this.fecha_fin_2,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.closeConfigModal();
                        this.showToast(data.message);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showToast('Ha ocurrido un error al guardar la configuración.', 'error');
                    });
                }
            }
        }
    </script>

    @if(session('success') || session('error'))
    <script>
        document.addEventListener('alpine:init', () => {
            // Disparar toast con mensaje de sesión
            setTimeout(() => {
                const el = document.querySelector('[x-data="configModal()"]');
                if (el && el._x_dataStack) {
                    const comp = el._x_dataStack[0];
                    @if(session('error'))
                    comp.showToast('{{ session('error') }}', 'error');
                    @else
                    comp.showToast('{{ session('success') }}');
                    @endif
                }
            }, 100);
        });
    </script>
    @endif
</x-app-layout>
