<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Empresas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Navegación de Sección Empresas -->
                    <div class="flex space-x-4 mb-6 border-b pb-4">
                        <a href="{{ route('empresas.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition duration-300">
                            Empresas
                        </a>
                        <a href="{{ route('sedes.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300">
                            Sedes
                        </a>
                        <a href="{{ route('empleados.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md font-medium transition duration-300">
                            Empleados
                        </a>
                    </div>
                    
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Listado de Empresas</h3>
                        
                        <!-- Buscador -->
                        <form method="GET" action="{{ route('empresas.index') }}" class="flex" x-data="{ 
                            search: '{{ request('search') }}',
                            async performSearch() {
                                let url = new URL(window.location.protocol + '//' + window.location.host + window.location.pathname);
                                url.searchParams.set('search', this.search);
                                
                                // Push state to update URL without reload
                                window.history.pushState({}, '', url);

                                const response = await fetch(url, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                });
                                const html = await response.text();
                                document.getElementById('empresas-table-body').innerHTML = html;
                            }
                        }">
                            <input type="text" name="search" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Buscar empresa..." class="rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md transition duration-300 text-sm font-medium">
                                Buscar
                            </button>
                        </form>

                        <!-- Botón para abrir modal de creación -->
                        <div x-data x-init="@if($errors->any() && !in_array(old('_method'), ['PUT', 'PATCH'])) $dispatch('open-modal', 'crear-empresa'); @endif">
                            <button x-on:click.prevent="$dispatch('open-modal', 'crear-empresa')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 ml-4">
                                Nueva Empresa
                            </button>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="empresas-table-body" class="bg-white divide-y divide-gray-200">
                            @include('empresas.partials.table-rows')
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear empresa -->
    <x-modal name="crear-empresa" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Añadir Nueva Empresa') }}
            </h2>

            <form method="POST" action="{{ route('empresas.store') }}" 
                  x-data="{
                      errors: {},
                      loading: false,
                      async submitForm() {
                          this.loading = true;
                          this.errors = {};
                          try {
                              const response = await fetch($el.action, {
                                  method: 'POST',
                                  headers: {
                                      'X-Requested-With': 'XMLHttpRequest',
                                      'Accept': 'application/json',
                                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                  },
                                  body: new FormData($el)
                              });
                              
                              if (response.ok) {
                                  window.location.reload();
                              } else if (response.status === 422) {
                                  const data = await response.json();
                                  this.errors = data.errors;
                              } else {
                                  alert('Ocurrió un error inesperado.');
                              }
                          } catch (error) {
                              console.error(error);
                              alert('Error de conexión.');
                          } finally {
                              this.loading = false;
                          }
                      }
                  }"
                  @submit.prevent="submitForm">
                @csrf

                @include('empresas.partials.form', ['empresa' => null])

                <div class="mt-6 flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 mr-2">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300" :class="{ 'opacity-50 cursor-not-allowed': loading }">
                        <span x-show="!loading">{{ __('Guardar') }}</span>
                        <span x-show="loading">{{ __('Guardando...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>