@props(['messages'])

@if ($messages)
    <ul  {{ $attributes->merge(['class' => 'feb']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
