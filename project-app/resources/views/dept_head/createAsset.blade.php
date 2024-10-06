@extends('layouts.app')
@section('header')
<div class="headerTitle">
    <h2 class="font-semibold text-xl uppercase font-bold text-gray-800 leading-tight">
        create Asset
    </h2>
</div>
@endsection

@section('content')
<div class="contents capitalize">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form action="{{ route('asset.create') }}" method="post" class="flex flex-col relative" enctype="multipart/form-data">
        @csrf
        <div class="formbox grid md:grid-cols-[1fr_8px_1fr] gap-3 max-md:grid-cols-1">
            <div class="formInformation">
                <div class="form-group">
                    <x-input-label for='assetname'>asset Name</x-input-label>
                    <x-text-input id="assetname" name='assetname' required />
                </div>
                <div class="form-group">
                    <x-input-label for='cost'>cost</x-input-label>
                    <x-text-input inputmode="decimal" id="cost" pattern="[0-9]*[.,]?[0-9]*" id="cost"
                        name='cost' required />
                </div>
                <div class="form-group">
                    <x-input-label for='salvageVal'>Salvage Value</x-input-label>
                    <x-text-input inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" id="salvageVal" name='salvageVal'
                        required />
                </div>
                <div class="grpInline grid grid-cols-2 gap-2">
                    <div class="form-group">
                        <x-input-label for='usage'>usage lifespan (year)</x-input-label>
                        <x-text-input type="number" id="usage" name='usage' required />
                    </div>
                    <div class="form-group">
                        <x-input-label for='purchased'>Purchased Date</x-input-label>
                        <x-text-input type="date" id="purchased" name='purchased' required />
                    </div>
                </div>
                <div class="grpInline grid grid-cols-2 gap-2">
                    <div class="form-group">
                        <x-input-label for='category'>Category</x-input-label>
                        <select name="category" id="category" class="w-full">
                            @foreach ($categories['ctglist'] as $category)
                            <option value={{ $category->id }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <x-input-label for='loc'>Location</x-input-label>
                        <select name="loc" id="loc" class="w-full">
                            @foreach ($location['locs'] as $location)
                            <option value={{ $location->id }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grpInline grid grid-cols-2 gap-2">
                    <div class="form-group">
                        <x-input-label for='mod'>Model</x-input-label>
                        <select name="mod" id="mod" class="w-full flex flex-col">
                            @foreach ($model['mod'] as $model)
                            <option value={{ $model->id }}>{{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <x-input-label for='mcft'>Manufacturer</x-input-label>
                        <select name="mcft" id="mcft" class="w-full">
                            @foreach ($manufacturer['mcft'] as $manufacturer)
                            <option value={{ $manufacturer->id }}>{{ $manufacturer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{-- divider --}}
            <div class="dvdr h-full w-[3px] bg-blue-950/10 hidden md:block"></div>
            {{-- divider --}}
            <div class="form-group images flex items-center flex-col AdditionalInfo">
                <div class="imageField w-32 h-32 relative flex justify-center">
                    <label
                        class="im"
                        for="image">
                        <img src="{{ asset('storage/images/defaultICON.png') }}" id="imageDisplay"
                            class="absolute border-3 w-32 h-32 border-3 rounded-md hover:border-blue-300 hover:border-4 hover:border-blue-400S top-1/2 left-1/2 w-auto h-full transform -translate-x-1/2 -translate-y-1/2 object-cover cursor-pointer"
                            alt="default">
                    </label>
                    <x-text-input type="file" id="image" name='image' class="hidden" />
                </div>
                {{-- Addtional Information / custom Fields --}}
                <div class="customFields flex flex-col w-full mt-4">
                    <div class="w-full text-[20px] capitalize font-semibold">Additional
                        Information</div>
                    <div class="addInfo grid grid-col-2 w-full" id="field">
                        <div class="addInfoContainer w-full overflow-auto p-2 h-[220px] scroll-smooth">
                            <div class="fieldSet mt-2 grid grid-cols-2 gap-2">
                                @if ($addInfos)
                                @foreach ($addInfos as $key => $dataItem )
                                <span>{{ $dataItem->name }}</span>
                                <input type="text" name="field[key][]" placeholder="key" class="hidden" value="{{ $dataItem->name }}">
                                <input type="text" name="field[value][]" placeholder="value">
                                @endforeach
                                @else
                                <span class="text-slate-400">
                                    {{ "No additional for this Department" }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="butn mt-2 w-full flex justify-center">
            <x-primary-button
                class="bg-blue-900 text-slate-100 transition ease-in ease-out hover:text-slate-100  hover:bg-blue-700 ">Create
                Asset</x-primary-button>
        </div>
    </form>
</div>
@vite(['resources/js/addInfoField.js', 'resources/js/displayImage.js'])
@endsection
