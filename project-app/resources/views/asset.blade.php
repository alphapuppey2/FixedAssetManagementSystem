
<x-app-layout>
    <x-slot name="header">
        <h2 class="">
           Asset
        </h2>
    </x-slot>
            <ul>
            @foreach ($list as $departments )
                <li>{{ $departments->name }}</li>
            @endforeach
        </ul>

    <div class="container">
        <button class="btn btn-info">
            <a href="asset/newasset" style="color:white; text-decoration:none;">
                Create Asset
            </a>
        </button>
        <button class="btn btn-info">
            <a href="asset/newasset" style="color:white; text-decoration:none;">
                Create Category
            </a>
        </button>
        <button class="btn btn-info">
            <a href="asset/department" style="color:white; text-decoration:none;">
                Create Department
            </a>
        </button>
    </div>
</x-app-layout>
