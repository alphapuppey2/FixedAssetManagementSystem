@extends('layouts.app')

<style>
    /* Hides spinners in Chrome, Edge, and Safari */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Hides spinners in Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
    }
    </style>


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
            <div class="formbox">
                <div class="formInformation grid grid-cols-[auto_auto] grid-rows-[1fr,30%] gap-4">
                    <div class="formFields">
                        <div class="form-group">
                            <x-input-label for='assetname'>asset Name</x-input-label>
                            <x-text-input id="assetname" name='assetname' required />
                        </div>
                        <div class="grpInline grid grid-cols-2 gap-2">
                            <div class="form-group">
                                <x-input-label for='pCost'>Purchase Cost</x-input-label>
                                <x-text-input
                                id="pCost"
                                name="pCost"
                                step="0.01"
                                min="0"
                                required
                            />
                            </div>
                            <div class="form-group">
                                <x-input-label for='pDate'>Purchase Date</x-input-label>
                                <x-text-input type="date" id="pDate" name='purchasedDate' pattern="^\d*\.?\d*$" required />
                            </div>
                        </div>
                        <div class="grpInline grid grid-cols-2 gap-2">
                            <div class="form-group">
                                <x-input-label for='lifespan'>Lifespan</x-input-label>
                                <x-text-input type="number" id="lifespan" name='lifespan' required />
                            </div>
                            <div class="form-group">
                                <x-input-label for='depreciation'>Depreciation</x-input-label>
                                <x-text-input type="text" id="depreciation" name='depreciation' pattern="^\d*\.?\d*$" required />
                            </div>
                        </div>
                        <div class="grpInline grid">
                            <div class="form-group">
                                <x-input-label for='salvageValue'>Salvage value</x-input-label>
                                <x-text-input id="salvageValue" name="salvageValue" pattern="^\d*\.?\d*$" required />
                            </div>q

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
                    {{-- <div class="dvdr h-full w-[3px] bg-blue-950/10 hidden md:block"></div> --}}
                    {{-- divider --}}
                    <div class="imgContainer flex flex-col items-center space-y-4">
                        <label class="font-semibold text-xs sm:text-sm md:text-base text-gray-700">Asset Image</label>
                        <div class="imageField w-40 h-40 border-2 border-gray-200 rounded-lg shadow-md overflow-hidden">
                            <img src="{{ asset('images/no-image.png') }}" id="imagePreview" alt="Asset Image" class="w-full h-full object-cover">
                        </div>
                        <label for="image" class="text-blue-500 cursor-pointer hover:underline">
                            Select New Image
                            <x-text-input type="file" id="image" name="asst_img" class="hidden" />
                        </label>
                    </div>
                    <div class="form-group row-start-2 images flex items-center flex-col AdditionalInfo">
                        {{-- Addtional Information / custom Fields --}}
                        <div class="customFields flex flex-col w-full mt-4">
                            <div class="w-full text-[20px] capitalize font-semibold">Additional
                                Information</div>
                            <div class="addInfo grid grid-col-2 w-full" id="field">
                                <div class="addInfoContainer w-full overflow-auto p-2 h-[220px] scroll-smooth">
                                    <div class="fieldSet mt-2 {{ isset($addInfos) ? 'grid grid-cols-2 gap-2' : 'flex' }}">
                                        @if ($addInfos)
                                            @foreach ($addInfos as $key => $dataItem)
                                                <span>{{ $dataItem->name }}</span>
                                                <input type="text" name="field[key][]" placeholder="key" class="hidden"
                                                    value="{{ $dataItem->name }}">
                                                <input type="text" name="field[value][]" placeholder="value">
                                            @endforeach
                                        @else
                                            <span class="text-slate-400">
                                                {{ 'No additional for this Department' }}
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
    {{-- @vite(['resources/js/displayImage.js']) --}}
    <script>
        // Get today's date in YYYY-MM-DD format


        const today = new Date().toISOString().split('T')[0];
        // Set the value of the date input to today's date
        document.getElementById('pDate').value = today;


        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });


    </script>
@endsection
