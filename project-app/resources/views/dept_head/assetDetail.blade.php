@extends('layouts.app')

@php
    $data = $retrieveData[0] ?? NULL;
    $imagePath = $date->image ?? 'image/defaultICON.png';
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
    <div class="details">
        <div class="imagepart">
            <img src="{{ asset('storage/images/ . $imagePath') }}" alt="" srcset="">
        </div>
    </div>
@endsection
