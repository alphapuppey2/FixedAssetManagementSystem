<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           Department
        </h2>
    </x-slot>

    <div class="contents">
        <div class="list">
            @foreach ($list as $key )
                <div class="dpt">
                    {{ $key->name }}
                </div>

            @endforeach
        </div>
        <form action="{{ route('newdepartment') }}" method="post">
            @csrf
            <x-input-label>Department Name</x-input-label>
            <x-text-input type="text" name="name" required />

            <x-primary-button>Submit</x-primary-button>
        </form>
    </div>
</x-app-layout>
