<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           create Asset
        </h2>
    </x-slot>

    <div class="contents">
        <form action="" method="post">
            @csrf
            <x-input-label>asset Name</x-input-label>
            <x-text-input type="text" name='name' required />
            <x-dropdown2 align="right" width="48" name='category' required>
                <x-slot name='trigger'>
                    Categories
                </x-slot>
                <x-slot name="content">
                    @foreach ($ctglist as $category)
                        <li value="{{ $category->id }}">
                            {{ $category->name }}
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown2>
            <x-text-input type="text" required />
        </form>
    </div>
</x-app-layout>
