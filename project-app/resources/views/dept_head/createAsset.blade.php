<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           create Asset
        </h2>
    </x-slot>
    <div class="contents">
        <form action="{{ route('asset.create') }}" method="post">
            @csrf
            <div class="form-group">
                <x-input-label for='image'>Image</x-input-label>
                <x-text-input type="file" id="image" name='image' />
            </div>
            <div class="form-group">
                <x-input-label for='cost'>cost</x-input-label>
                <x-text-input type="number" id="cost" name='cost' required />
            </div>
            <div class="form-group">
                <x-input-label for='name'>asset Name</x-input-label>
                <x-text-input type="text" id="name" name='name' required />
            </div>
            <div class="form-group">
                <x-input-label for='name'>asset Name</x-input-label>
                <x-text-input type="text" id="name" name='name' required />
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
                <x-input-label for='category'>Category</x-input-label>
                <select name="category" id="category" class="max-w-100 flex flex-col">
                    @foreach ($categories['ctglist'] as $category)
                        <option value={{ $category->id }}>{{ $category->name}}</option>
                    @endforeach
                </select>
            </div>

            <x-primary-button>Create Asset</x-primary-button>
        </form>
    </div>
</x-app-layout>
