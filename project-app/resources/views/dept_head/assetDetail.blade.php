@extends('layouts.app')

@php
    $data = $retrieveData[0] ?? null;
    $imagePath = $data->image ?? 'images/defaultICON.png';
@endphp

@section('header')
    <h2 class="font-semibold  text-xl text-gray-800 leading-tight flex w-24">
        <a href="{{ route('back') }}">Asset</a>
        <div class="direct ml-5">
            >
        </div>
    </h2>
    <h2 class="assetID font-semibold  text-xl w-24">
        {{ $data->code }}
    </h2>
    <a href="" class="text-blue-500 text-[12px]">EDIT</a>
@endsection
@section('content')
    <div class="details grid grid-cols-2 gap-3 ">
        <div class="information">
            <div class="leftDetail w-50 text-sm">
                <div class="info flex grid grid-cols-2 gap-2 pb-1">
                    <div class="field-label uppercase text-slate-300">name</div>
                    <div class="field-Info font-semibold ">{{ $data->name }}</div>
                </div>
                <div class="info flex grid grid-cols-2 gap-2 pb-1">
                    <div class="field-label uppercase text-slate-300">cost</div>
                    <div class="field-Info font-semibold">{{ $data->cost }}</div>
                </div>
                <div class="info flex grid grid-cols-2 gap-2 pb-1">
                    <div class="field-label uppercase text-slate-300">depreciation</div>
                    <div class="field-Info font-semibold">{{ $data->depreciation }}</div>
                </div>
                <div class="info flex grid grid-cols-2 gap-2 pb-1">
                    <div class="field-label uppercase text-slate-300">model</div>
                    <div class="field-Info font-semibold ">{{ $data->usage_Lifespan }}</div>
                </div>
                <div class="info flex grid grid-cols-2 gap-2 pb-1">
                    <div class="field-label uppercase text-slate-300">Manufacturer</div>
                    <div class="field-Info font-semibold">{{ $data->manufacturer }}</div>
                </div>
                <div class="info flex grid grid-cols-2 gap-2 pb-1">
                    <div class="field-label uppercase text-slate-300">Location</div>
                    <div class="field-Info font-semibold">{{ $data->location }}</div>
                </div>
                <div class="info flex grid grid-cols-2 gap-2 pb-1">
                    <div class="field-label uppercase text-slate-300">status</div>
                    <div class="field-Info font-semibold">{{ $data->status }}</div>
                </div>
            </div>
            <div class="MoreInfo">
                <div class="addInformation">
                    <div class="title font-bold m-2 text-[15px] opacity-50 uppercase">
                        Additional information
                        <div class="divider w-20 h-[2px] bg-slate-400 opacity-50 mb-2 mt-2"></div>
                    </div>
                    <div class="data grid grid-cols-2">
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
            </div>
        </div>
        <div class="right">
            <div class="imgContainer w-[100%] pb-4 flex justify-center items-center">
                <div class="imagepart w-72 h-48 overflow-hidden relative p-3">
                    <img src="{{ asset('storage/' . $imagePath) }}" class="absolute top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover" alt="assetImage">
                </div>
                <div class="qrContainer flex flex-col items-center">
                    <div class="QRBOX w-24 h-24 bg-red-300"></div>
                    <a href="#" target="_blank" rel="noopener noreferrer">Print QR Code</a>
                </div>
            </div>
            <div class="maintenance w-full h-full bg-green-400">
                Maintenance Here
            </div>
        </div>
    </div>
@endsection
