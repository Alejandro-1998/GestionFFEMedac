<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Empresa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('empresas.store') }}" method="POST">
                        @csrf
                        
                        @include('empresas.partials.form', ['empresa' => null])

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('empresas.index') }}" class="mr-2 px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Guardar Empresa</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>