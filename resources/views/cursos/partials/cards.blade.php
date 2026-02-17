@forelse ($cursos as $curso)
    <div class="relative group">
        <a href="{{ route('cursos.show', $curso->id) }}" class="block bg-white border {{ $curso->actual ? 'border-green-500 ring-2 ring-green-200' : 'border-gray-200' }} rounded-lg p-4 shadow-sm hover:shadow-md hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-200 text-center">
            <span class="text-lg font-semibold text-gray-800 group-hover:text-indigo-700">{{ $curso->anyo }}</span>
            @if($curso->actual)
                <span class="absolute top-2 right-2 text-green-500" title="Curso Actual">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </span>
            @else
                <form action="{{ route('cursos.actual', $curso->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-yellow-400" title="Marcar como Actual">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </button>
                </form>
            @endif
        </a>
    </div>
@empty
    <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <span class="text-lg font-medium">No hay cursos académicos registrados</span>
        <p class="text-sm mt-1">Utiliza el botón "Nuevo Curso" para añadir uno.</p>
    </div>
@endforelse
