@extends('layouts.app')

@php
    $data = $retrieveData[0] ?? null;
    $imagePath = $data->image ?? 'images/defaultICON.png';
@endphp

@section('header')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <h2 class="font-semibold  text-xl text-gray-800 leading-tight flex w-24">
        <a href="{{ route('back') }}">Asset</a>
        <div class="direct ml-5">
            >
        </div>
    </h2>
    <h2 class="assetID font-semibold  text-xl w-24">
        {{ $data->code }}
    </h2>
    <button id="editBTN" type="submit" class="text-blue-500 text-[12px]">EDIT</button>
    <button id="saveBTN" type="submit" form="formEdit" class="text-blue-500 mr-2 text-[12px] hidden">SAVE</button>
    <button id="cancelBTN" class="text-blue-500 text-[12px] mr-2 hidden">CANCEL</button>
@endsection
@section('content')
    <div class="w-full h-full">
        @if ($errors->any())
            <div class="err">
                INVALID
            </div>
        @endif
        <form id="formEdit" action="{{ route('assetDetails.edit', $data->id) }}" class="details relative w-full min-h-full" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="leftC">
                <div class="mainDetail grid grid-rows-6 grid-flow-col">
                    <div id="name" class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">name</div>
                        <div class="field-Info font-semibold">{{ $data->name }}</div>
                        <x-text-input class="text-sm edit hidden" name='name' value="{{ $data->name }}" />
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">cost</div>
                        <div class="field-Info font-semibold">{{ $data->cost }}</div>
                        <x-text-input inputmode="decimal" id="cost" class="edit hidden" pattern="[0-9]*[.,]?[0-9]*" id="cost"
                            name='cost' required value="{{ $data->cost }}"/>
                        </div>
                        <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                            <div class="field-label uppercase text-slate-400">depreciation</div>
                            <div class="field-Info1 font-semibold">{{ $data->depreciation }}</div>
                            {{-- <x-text-input inputmode="decimal" id="depreciation" class="edit hidden" pattern="[0-9]*[.,]?[0-9]*" id="cost"
                                name='depreciation' required value="{{ $data->depreciation }}"/> --}}
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Salvage Value</div>
                        <div class="field-Info font-semibold">{{ $data->salvageVal }}</div>
                        <x-text-input inputmode="decimal" id="salvageVal" class="edit hidden" pattern="[0-9]*[.,]?[0-9]*" id="cost"
                                name='salvageVal' required value="{{ $data->salvageVal }}"/>

                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Category</div>
                        <div class="field-Info font-semibold">{{ $data->category }}</div>
                        {{-- EDIT Category --}}
                        <div class="form-group edit hidden">
                            <select name="category" id="category" value class="w-full">
                                @foreach ($categories['ctglist'] as $category)
                                    <option value={{ $category->id }} @selected($data->category == $category->name)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <label class="field-label uppercase text-slate-400">lifespan</label>
                        <div class="field-Info font-semibold ">{{ $data->usage_Lifespan }}</div>
                        <x-text-input class="text-sm edit hidden" id="usage" name="usage"
                            value="{{ $data->usage_Lifespan }}"></x-text-input>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Model</div>
                        <div class="field-Info font-semibold">{{ $data->model }}</div>
                        <div class="form-group edit hidden">
                            <select name="mod" id="mod" class="w-full flex flex-col">
                                @foreach ($model['mod'] as $model)
                                    <option value={{ $model->id }} @selected($data->model == $model->name)>
                                        {{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">Manufacturer</div>
                        <div class="field-Info font-semibold">{{ $data->manufacturer }}</div>
                        <div class="form-group edit hidden">
                            <select name="mcft" id="mcft" class="w-full">
                                @foreach ($manufacturer['mcft'] as $manufacturer)
                                    <option value={{ $manufacturer->id }} @selected($data->manufacturer == $manufacturer->name)>
                                        {{ $manufacturer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <label class="field-label uppercase text-slate-400" for="loc">Location</label>
                        <div class="field-Info display font-semibold visible">{{ $data->location }}</div>
                        <div class="form-group edit hidden">
                            <select name="loc" id="loc" class="w-full">
                                @foreach ($location['locs'] as $location)
                                    <option value={{ $location->id }} @selected($data->location == $location->name)>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">status</div>
                        <div class="field-Info font-semibold">{{ $data->status }}</div>
                        <div class="form-group edit hidden">
                            <select name="status" id="stats" class="w-full">
                                @foreach ($status['sts'] as $stats)
                                    <option value="{{ $stats }}" @selected($data->status == $stats)>
                                        {{ $stats }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="info flex grid grid-cols-2 gap-2 pb-1 items-center">
                        <div class="field-label uppercase text-slate-400">last Used</div>
                        <div class="field-display font-semibold">NONE</div>
                    </div>
                </div>
                <div class="MoreInfo">
                    <div class="addInformation">
                        <div class="title font-bold m-2 text-[15px] opacity-50 uppercase">
                            Additional information
                            <div class="divider w-20 h-[2px] bg-slate-400 opacity-50 mb-2 mt-2"></div>
                        </div>
                        <div class="addInfoContainer w-full">
                            @if ($fields)
                                @foreach ($fields as $key => $value)
                                    <div class="extraInfo flex flex-wrap bg-red-500  gap-2">
                                        <div class="field-Info customField">{{ $key }}</div>
                                        <div class="field-Info customField">{{ $value }}</div>
                                        <x-text-input class="edit hidden" value="{{ $key }}" />
                                        <x-text-input class="edit hidden" value="{{ $value }}" />
                                    </div>
                                @endforeach
                            @else
                                <div class="noneField">
                                    no Additional
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex w-full justify-center edit hidden">
                        <button id='addMoreFields'
                            class="p-1 block text-blue-700 border-1 border-blue-700 rounded-md transition ease-in ease-out hover:bg-blue-700 hover:text-slate-100">Add
                            Field</button>
                    </div>
            </div>
            </div> {{-- END mainInformation class  --}}
            <div class="rightC flex flex-col">
                <div class="imgContainer w-[100%] pb-4 flex justify-center items-center">
                    <div class="imagepart overflow-hidden relative p-3">
                        <div class="imageField w-32 h-32 relative flex justify-center">
                            <div class="field-Info w-32 h-32 border-3 rounded-md transition ease-in ease-out"
                                for="image">
                                <img src="{{ asset('storage/' . $imagePath) }}" id="imageviewOnly"
                                    class="absolute top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover"
                                    alt="default">
                            </div>
                            <label

                                class="edit hidden w-32 h-32 border-3 rounded-md hover:border-4 hover:border-blue-400 transition ease-in ease-out"
                                for="image">
                                <img src="{{ asset('storage/' . $imagePath) }}" id="imageDisplay"
                                    class="absolute top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover"
                                    alt="default">
                            </label>
                        </div>
                        <x-text-input type="file" id="image" name='image' class="hidden" />
                    </div>
                    <div class="qrContainer flex flex-col items-center">
                        <div class="QRBOX w-24 h-24 bg-red-300"></div>
                        <a href="#" target="_blank" rel="noopener noreferrer">Print QR Code</a>
                    </div>
                </div>
                <div class="maintenance bg-green-400">
                    Maintenance Here
                </div>
        </form>
    </div>
    </div>
    @if(session('success'))
    <div id="toast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
        {{ session('sucess') }}
    </div>
@endif
    @vite(['resources/js/editAsset.js', 'resources/js/displayImage.js', 'resources/js/updateDetails.js', 'resources/js/addInfoField.js'])
@endsection
