@props(['status'])


<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn']) }}>
    {{ $slot }}
</button>
