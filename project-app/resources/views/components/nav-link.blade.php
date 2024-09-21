@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link bg-blue-200 text-blue-900 hover:text-blue-900'
            : 'nav-link hover:bg-slate-400/15 ';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
