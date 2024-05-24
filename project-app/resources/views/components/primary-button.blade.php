<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-outline-info']) }}>
    {{ $slot }}
</button>
