
@extends('layouts.app')
@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    create Asset
 </h2>
@endsection

@section('content')
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
        <div class="form-group images">
            <div class="imageField relative w-56 h-56 ">
                <img src="{{ asset('storage/images/defaultICON.png') }}" id="imageDisplay" class="object-cover rounded-md w-full h-full" alt="default">
                <!-- Overlay with text -->
                <label for="image" class="cursor-pointer p-[2%] rounded bg-red-200 w-full h-full absolute top-0 left-0 flex justify-center items-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                    Add Image
                </label>
            </div>
        </div>

            <x-text-input type="file" id="image" name='image' class="hidden"/>
        </div>
        <div class="form-group">
            <x-input-label for='assetname'>asset Name</x-input-label>
            <x-text-input type="text" id="assetname" name='assetname' required />
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

        <div class="customFields">
            <x-input-label for="field">Additional Information</x-input-label>

            <div class="addInfoContainer ">
                <div class="fieldSet  mt-2">
                    <input type="text" class="mr-2" name="field[key][]" id="field" placeholder="key">
                    <input type="text" name="field[value][]" id="field" placeholder="value">
                </div>
            </div>
            <button id='addMoreFields'>Add more field</button>


        </div>

        <x-primary-button>Create Asset</x-primary-button>
    </form>
</div>

<script>
    function addNewFields() {
    // Container
    let newFieldSet = document.createElement('div');
    newFieldSet.className = 'fieldSet mt-2';

    // Input Key
    let newKeyInput = document.createElement('input');
        newKeyInput.type = 'text';
        newKeyInput.name = 'field[key][]';
        newKeyInput.placeholder = 'key';
        newKeyInput.className = 'mr-2';

    // input value
    let newValueInput = document.createElement('input');
        newValueInput.type = 'text';
        newValueInput.name = 'field[value][]';
        newValueInput.placeholder = 'value';

    newFieldSet.appendChild(newKeyInput);
    newFieldSet.appendChild(newValueInput);

    document.querySelector('.addInfoContainer').appendChild(newFieldSet);
}

document.getElementById('addMoreFields').addEventListener('click', function(event) {
    event.preventDefault();
    addNewFields();
});

//image eventListener
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


@endsection
