<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex items-center justify-center px-4 py-3 bg-primary-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 focus:ring-offset-white transition ease-in-out duration-150 shadow-lg shadow-primary-600/30']) }}>
    {{ $slot }}
</button>
