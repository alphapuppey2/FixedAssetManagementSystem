

<table {{ $attributes->merge(['class' => 'table table-hover w-[100%]']) }}>
    <thead>
        {{ $header }}
    </thead>
    <tbody>
        {{ $slot }}
    </tbody>
</table>
