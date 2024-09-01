@props(['data'])

@if (!$data)
    $dataContent = $data;
@endif

<table {{ $attributes->merge(['class' => 'table table-hover w-[100%]']) }}>
    <thead>
        {{ $header }}
    </thead>
    <tbody>
        {{ $slot }}
    </tbody>
</table>
