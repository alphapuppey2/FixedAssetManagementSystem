@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link bg-blue-200 text-blue-900 hover:bg-blue-200 hover:text-blue-900'
            : 'nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
