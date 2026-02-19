<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tablero Principal') }}
            </h2>
            <span class="text-gray-500 font-normal text-sm ml-4">{{ __('Aquí tienes un resumen de los datos y de los convenios.') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            


            <!-- Tarjetas Datos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Alumnos Tarjeta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-indigo-100 text-indigo-500 mr-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Alumnos</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['alumnos'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Empresas Tarjeta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-100 text-blue-500 mr-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Empresas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['empresas'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sedes Tarjeta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-100 text-green-500 mr-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Sedes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['sedes'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Empleados Tarjeta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-orange-100 text-orange-500 mr-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Empleados</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['empleados'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Accesos Rápidos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Accesos Rápidos
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('cursos.index') }}" class="block p-4 bg-orange-50 hover:bg-orange-100 rounded-lg text-center transition duration-300">
                                <span class="block text-orange-700 font-bold mb-1">Cursos Académicos</span>
                                <span class="text-xs text-orange-600">Administrar años escolares</span>
                            </a>
                            <a href="{{ route('alumnos.index') }}" class="block p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-center transition duration-300">
                                <span class="block text-indigo-700 font-bold mb-1">Gestionar Alumnos</span>
                                <span class="text-xs text-indigo-600">Ver listado completo</span>
                            </a>
                            <a href="{{ route('empresas.index') }}" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition duration-300">
                                <span class="block text-blue-700 font-bold mb-1">Gestionar Empresas</span>
                                <span class="text-xs text-blue-600">Empresas, Sedes y Empleados</span>
                            </a>
                            <a href="{{ route('convenios.index') }}" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition duration-300">
                                <span class="block text-green-700 font-bold mb-1">Gestionar Convenios</span>
                                <span class="text-xs text-green-600">Crear y supervisar acuerdos</span>
                            </a>
                            @if(Auth::user()->rol === 'admin')
                            <a href="{{ route('profesores.index') }}" class="block p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition duration-300">
                                <span class="block text-purple-700 font-bold mb-1">Gestionar Profesores</span>
                                <span class="text-xs text-purple-600">Administrar cuerpo docente</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actividad Reciente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Actividad Reciente
                        </h4>
                        @if($actividades->count() > 0)
                            <ul class="divide-y divide-gray-100">
                                @foreach($actividades as $actividad)
                                    @php
                                        $iconColor = match(class_basename($actividad->sujeto_type)) {
                                            'User' => 'text-indigo-500 bg-indigo-100',
                                            'Empresa' => 'text-blue-500 bg-blue-100',
                                            'Sede' => 'text-green-500 bg-green-100',
                                            'Empleado' => 'text-orange-500 bg-orange-100',
                                            'Convenio' => 'text-green-500 bg-green-100',
                                            default => 'text-gray-500 bg-gray-100'
                                        };
                                        
                                        $entityIcon = match(class_basename($actividad->sujeto_type)) {
                                            'User' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />',
                                            'Empresa' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
                                            'Sede' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />',
                                            'Convenio' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
                                            default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                                        };
                                    @endphp
                                    <li class="py-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center {{ $iconColor }} mr-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $entityIcon !!}
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $actividad->descripcion }}</p>
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <span class="capitalize font-semibold mr-1">{{ $actividad->evento }}</span>
                                                    @if($actividad->sujeto)
                                                        @php
                                                            $type = class_basename($actividad->sujeto_type);
                                                            $sujeto = $actividad->sujeto;
                                                            
                                                            $displayText = match($type) {
                                                                'User' => $sujeto->nombre,
                                                                'Empresa' => $sujeto->nombre,
                                                                'Sede' => $sujeto->nombre,
                                                                'Empleado' => $sujeto->nombre . ' ' . $sujeto->apellido ?? '',
                                                                'Convenio' => 'Convenio #' . $sujeto->id,
                                                                default => $type . ' #' . $actividad->sujeto_id
                                                            };

                                                            $textColor = match($type) {
                                                                'User' => 'text-indigo-600',
                                                                'Empresa' => 'text-blue-600',
                                                                'Sede' => 'text-green-600',
                                                                'Empleado' => 'text-orange-600',
                                                                'Convenio' => 'text-green-700', // Matching the Convenios button color
                                                                default => 'text-gray-600'
                                                            };
                                                        @endphp
                                                        <span class="mx-1">•</span>
                                                        <span class="{{ $textColor }} font-medium">{{ $displayText }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $actividad->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 italic">No hay actividad reciente.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
