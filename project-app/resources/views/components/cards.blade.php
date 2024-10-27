@props(['title' , 'counts'])

@php
    $cardColor = '';
    $textColor ='';

    switch ($title) {
        case 'active':
            $cardColor = 'bg-green-300';
            $textColor = 'text-green-950';
            break;
        case 'under maintenance':
            $cardColor = 'bg-yellow-200';
            $textColor = 'text-yellow-950';
            break;
        case 'disposed':
            $cardColor = 'bg-gray-400';
            $textColor = 'text-gray-950';
            break;
        case 'deployed':
            $cardColor = 'bg-blue-300';
            $textColor = 'text-blue-950 outline-1';
            break;
    }
@endphp


<div {{ $attributes->merge(['class' => "$cardColor text-white p-4 rounded-lg shadow-md flex flex-col items-center"]) }}>
    {{-- <div class=" text-lg font-semibold uppercase {{ $textColor }}"> --}}
    <div class="font-semibold uppercase {{ $textColor }} text-sm sm:text-base md:text-lg lg:text-xl">
        {{ $title }}
    </div>
    {{-- <div class="text-3xl font-bold mt-3"> --}}
    <div class="mt-3 font-bold text-2xl sm:text-3xl md:text-4xl lg:text-5xl {{ $textColor }}">
        {{ $counts }}
    </div>
</div>
