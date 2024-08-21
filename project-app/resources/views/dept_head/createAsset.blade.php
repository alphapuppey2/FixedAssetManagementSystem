@php

@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           create Asset
        </h2>
    </x-slot>
    <div class="contents">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


        <form action="{{ route('asset.create') }}" method="post"  enctype="multipart/form-data" >
            @csrf
            <div class="form-group " class=" images">

                <div class="imageDisplayContainer">
                    <img src="{{ asset('storage/images/defaultImage.png') }}" id="imageDisplay"  alt="default">
                </div>

                <x-input-label for='image' class="pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                    </svg>
                </x-input-label>

                <x-text-input type="file" id="image" name='image' class="hidden"/>
            </div>
            <div class="form-group">
                <x-input-label for='name'>asset Name</x-input-label>
                <x-text-input type="text" id="name" name='name' required />
            </div>
            <div class="group">
                <div class="form-group">
                    <x-input-label for='cost'>cost</x-input-label>
                    <x-text-input type="text" inputmode="decimal"
                    id="cost" pattern="[0-9]*[.,]?[0-9]*" id="cost" name='cost' required />
                </div>
                <div class="form-group">
                    <x-input-label for='salvageVal'>Salvage Value</x-input-label>
                    <x-text-input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" id="salvageVal" name='salvageVal' required />
                </div>
            </div>
            <div class="form-group">
                <x-input-label for='usage'>usage lifespan (year)</x-input-label>
                <x-text-input type="number" id="usage" name='usage' required />
            </div>
            <div class="form-group">
                <x-input-label for='category'>Category</x-input-label>
                <select name="category" id="category" class="max-w-100 flex flex-col">
                    @foreach ($categories['ctglist'] as $category)
                        <option value={{ $category->id }}>{{ $category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <x-input-label for='loc'>Location</x-input-label>
                <select name="loc" id="loc" class="max-w-100 flex flex-col">
                    @foreach ($location['locs'] as $location)
                        <option value={{ $location->id }}>{{ $location->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <x-input-label for='mod'>Model</x-input-label>
                <select name="mod" id="mod" class="max-w-100 flex flex-col">
                    @foreach ($model['mod'] as $model)
                        <option value={{ $model->id }}>{{ $model->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <x-input-label for='mcft'>Manufacturer</x-input-label>
                <select name="mcft" id="mcft" class="max-w-100 flex flex-col">
                    @foreach ($manufacturer['mcft'] as $manufacturer)
                        <option value={{ $manufacturer->id }}>{{ $manufacturer->name}}</option>
                    @endforeach
                </select>
            </div>

            <x-primary-button>Create Asset</x-primary-button>
        </form>
    </div>
    <script>
        document.getElementById('image').addEventListener('change', function(event) {
    const imagePreview = document.getElementById('imageDisplay');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    } else {
        imagePreview.src = '';
        imagePreview.style.display = 'none';
    }
});

    </script>
</x-app-layout>
