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


        <form action="{{ route('asset.create') }}" method="post">
            @csrf
            <div class="form-group">
                <x-input-label for='image'>Image</x-input-label>
                <x-text-input type="file" id="image" name='image' />
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
</x-app-layout>
