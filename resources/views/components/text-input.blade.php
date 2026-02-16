@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-surface-900 shadow-sm focus:border-primary-600 focus:ring-primary-600 placeholder-surface-400 transition-colors']) !!}>
