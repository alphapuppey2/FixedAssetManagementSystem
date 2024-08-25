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
    <span id="edit" class="text-blue-500 text-[12px]">EDIT</a>
    <button id="save" type="submit" form="formEdit" class="text-blue-500 text-[12px]">SAVE</button>
    <span id="cancel" class="text-blue-500 text-[12px]">CANCEL</a>
@endsection
@section('content')
    <div class=" w-full h-full">
        @if ($errors->any())
            <div class="err">
                INVALID
            </div>
        @endif
        <form id="formEdit" action="{{ route('assetDetails.edit',$data->id) }}" class="details relative grid grid-cols-[1fr_40%] gap-2 h-full" method="POST">
            @csrf
            @method('PUT')
            <div class="information">
                <div class="leftDetail text-sm grid grid-rows-6 grid-flow-col gap-1">
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">name</div>
                        {{-- <div class="field-Info font-semibold ">{{ $data->name }}</div> --}}
                        <x-text-input class="text-sm" name='name' value="{{ $data->name  }}"></x-text-input>

                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">cost</div>
                        {{-- <div class="field-Info font-semibold">{{ $data->cost }}</div> --}}
                        <x-text-input class="text-sm" name='cost' value="{{ $data->cost  }}"></x-text-input>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">depreciation</div>
                        <div class="field-Info font-semibold">{{ $data->depreciation }}</div>
                        {{-- <x-text-input class="text-sm" value="{{ $data->depreciation  }}"></x-text-input> --}}
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Salvage Value</div>
                        <div class="field-Info font-semibold">{{ $data->salvageVal }}</div>
                        {{-- <x-text-input class="text-sm" value="{{ $data->salvageVal  }}"></x-text-input> --}}
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Category</div>
                        {{-- <div class="field-Info font-semibold">{{ $data->salvageVal }}</div> --}}
                        <div class="form-group">
                            <select name="category" id="category" class="w-full">
                                @foreach ($categories['ctglist'] as $category)
                                    <option value={{ $category->id }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <label class="field-label uppercase text-slate-400">lifespan</label>
                        {{-- <div class="field-Info font-semibold ">{{ $data->usage_Lifespan }}</div> --}}
                        <x-text-input class="text-sm" id="usage" name="usage" value="{{ $data->usage_Lifespan  }}"></x-text-input>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Model</div>
                        {{-- <div class="field-Info font-semibold">{{ $data->model }}</div> --}}
                        <div class="form-group">
                            <select name="mod" id="mod" class="w-full flex flex-col">
                                @foreach ($model['mod'] as $model)
                                    <option value={{ $model->id }}>{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Manufacturer</div>
                        {{-- <div class="field-Info font-semibold">{{ $data->manufacturer }}</div> --}}
                        <div class="form-group">
                            <select name="mcft" id="mcft" class="w-full">
                                @foreach ($manufacturer['mcft'] as $manufacturer)
                                    <option value={{ $manufacturer->id }}>{{ $manufacturer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <label class="field-label uppercase text-slate-400" for="loc">Location</label>
                        {{-- <div class="field-Info font-semibold">{{ $data->location }}</div> --}}
                        <div class="form-group">
                            <select name="loc" id="loc" class="w-full">
                                @foreach ($location['locs'] as $location)
                                    <option value={{ $location->id }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">status</div>
                        {{-- <div class="field-Info font-semibold">{{ $data->status }}</div> --}}
                        <div class="form-group">
                            <select name="status" id="stats" class="w-full">
                                @foreach ($status['sts'] as $stats)
                                    <option value="{{ $stats }}">{{ $stats }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">last Used</div>
                        <div class="field-Info font-semibold">NONE</div>
                    </div>
                </div>
                <div class="MoreInfo">
                    <div class="addInformation">
                        <div class="title font-bold m-2 text-[15px] opacity-50 uppercase">
                            Additional information
                            <div class="divider w-20 h-[2px] bg-slate-400 opacity-50 mb-2 mt-2"></div>
                        </div>
                        <div class="bg-red-300 w-full grid grid-rows-3 grid-flow-col">
                            @if ($fields)
                                @foreach ($fields as $key => $value)
                                    <div class="extraInfo flex grid grid-cols-2">
                                        <div class="customField">{{ $key }}</div>
                                        <div class="customField">{{ $value }}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="noneField">
                                    no Additional
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="right bg-indigo-400 ">
                <div class="imgContainer w-[100%] pb-4 flex justify-center items-center">
                    <div class="imagepart w-72 h-48 overflow-hidden relative p-3">
                        <img src="{{ asset('storage/' . $imagePath) }}"
                            class="absolute top-1 items-center/2 left-1 items-center/2 w-auto h-full transform -translate-x-1 items-center/2 -translate-y-1 items-center/2 object-cover"
                            alt="assetImage">
                    </div>
                    <div class="qrContainer flex flex-col items-center">
                        <div class="QRBOX w-24 h-24 bg-red-300"></div>
                        <a href="#" target="_blank" rel="noopener noreferrer">Print QR Code</a>
                    </div>
                </div>
                <div class="maintenance bg-green-400">
                    Maintenance Here
                </div>
            </div>
        </form>
    </div>

    @vite(['resources/js/editAsset.js'])
@endsection
