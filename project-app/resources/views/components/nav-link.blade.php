@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link text-bg-info'
            : 'nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
