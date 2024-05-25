@props(["header"])

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ "Create Maintenance" }}
        </h2>
    </x-slot>

    <div class="contents relative flex flex-col">
        create Here
    </div>
</x-app-layout>
