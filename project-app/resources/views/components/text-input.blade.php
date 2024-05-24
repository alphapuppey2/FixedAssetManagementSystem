@props(['disabled' => false])
@props(['error'])

    @php
    $style = ($error ?? false) ?'border-color:red' :''
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control', 'style' => $style]) !!}>
