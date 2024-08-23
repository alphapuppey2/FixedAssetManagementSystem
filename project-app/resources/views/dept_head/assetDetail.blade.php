@extends('layouts.app')

@php
    $data = $retrieveData[0] ?? null;
    $imagePath = $data->image ?? 'images/defaultICON.png';
@endphp

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight flex w-24">
        <a href="{{ route('back') }}">Asset</a>
        <div class="direct ml-5">
            >
        </div>
    </h2>
    <h2 class="assetID font-semibold text-xl w-24">
        {{ $data->code }}
    </h2>
@endsection
@section('content')
    <div class="details grid grid-cols-2 gap-2 items-between">
        <div class="leftContentContainer">
            <h1 class="font-semibold text-xl text-gray-800">Details:</h1>
            <div class="info flex grid grid-cols-2 gap-2">
                <div class="field-label">name:</div>
                <div class="field-Info ">{{ $data->name }}</div>
            </div>
            <div class="info flex grid grid-cols-2 gap-2">
                <div class="field-label bg-red-300">cost:</div>
                <div class="field-Info bg-red-300">{{ $data->cost }}</div>
            </div>
            <div class="info flex grid grid-cols-2 gap-2">
                <div class="field-label bg-red-300">depreciation:</div>
                <div class="field-Info bg-red-300">{{ $data->depreciation }}</div>
            </div>
            <div class="info flex grid grid-cols-2 gap-2">
                <div class="field-label bg-red-300">model:</div>
                <div class="field-Info bg-red-300">{{ $data->usage_Lifespan }}</div>
            </div>
            <div class="info flex grid grid-cols-2 gap-2">
                <div class="field-label bg-red-300">Manufacturer:</div>
                <div class="field-Info bg-red-300">{{ $data->manufacturer }}</div>
            </div>
            <div class="info flex grid grid-cols-2 gap-2">
                <div class="field-label bg-red-300">Location:</div>
                <div class="field-Info bg-red-300">{{ $data->location }}</div>
            </div>
            <div class="info flex grid grid-cols-2 gap-2">
                <div class="field-label bg-red-300">status:</div>
                <div class="field-Info bg-red-300">{{ $data->status }}</div>
            </div>
        </div>
        <div class="imgContainer flex justify-center">
            <div class="imagepart w-[20%] h-[20%]">
                <img src="{{ asset('storage/' . $imagePath) }}" class="object-cover" alt="assetImage">
                <a href="#" target="_blank" rel="noopener noreferrer">Print QR Code</a>
            </div>
        </div>
    </div>
    <div class="addInformation w-[50%]">
        <div class="title">
            Additional information

            @if ($fields)
                @foreach ($fields as $key => $value)
                    <div class="extraInfo grid grid-cols-2">
                        <div class="customField">{{ $key }}</div>
                        <div class="customField">{{ $value }}</div>
                    </div>
                @endforeach
            @else
                <div class="error">
                    No Custom FIeld</div>
            @endif

        </div>

    </div>
@endsection
